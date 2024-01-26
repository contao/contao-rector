<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\Constant\ConstantStringType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ModeConstantToScopeMatcherRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated TL_MODE constant to service call', [
            new CodeSample(
                <<<'CODE_BEFORE'
$isBackend = TL_MODE === 'BE';
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$isBackend = \Contao\System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(\Contao\System::getContainer()->get('request_stack')->getCurrentRequest() ?? \Symfony\Component\HttpFoundation\Request::create(''));
CODE_AFTER
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [BooleanAnd::class, Equal::class, NotEqual::class, Identical::class, NotIdentical::class];
    }

    public function refactor(Node $node): Node|int|null
    {
        if (!$node instanceof BooleanAnd) {
            return $this->refactorConstant($node);
        }

        $left = $node->left;

        if (
            !$left instanceof FuncCall
            || !$this->nodeNameResolver->isName($left, 'defined')
            || 1 !== \count($left->getArgs())
            || !($arg = $left->getArgs()[0]->value)
            || !$arg instanceof String_
            || 'TL_MODE' !== $arg->value
        ) {
            return null;
        }

        return $this->refactorConstant($node->right);
    }

    private function refactorConstant(Node $node): Node|null
    {
        if (
            !$node instanceof Equal
            && !$node instanceof NotEqual
            && !$node instanceof Identical
            && !$node instanceof NotIdentical
        ) {
            return null;
        }

        if ($node->left instanceof ConstFetch && $this->isName($node->left, 'TL_MODE')) {
            $value = $this->nodeTypeResolver->getType($node->right);
        } elseif ($node->right instanceof ConstFetch && $this->isName($node->right, 'TL_MODE')) {
            $value = $this->nodeTypeResolver->getType($node->left);
        } else {
            return null;
        }

        if (!$value instanceof ConstantStringType || !\in_array($value->getValue(), ['BE', 'FE'])) {
            return null;
        }

        $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Contao\System'), 'getContainer');

        $requestStack = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('request_stack'))]);
        $currentRequest = new Node\Identifier('getCurrentRequest');
        $newRequest = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Symfony\Component\HttpFoundation\Request'), 'create', [new Node\Arg(new Node\Scalar\String_(''))]);
        $request = new Node\Expr\BinaryOp\Coalesce(new Node\Expr\MethodCall($requestStack, $currentRequest), $newRequest);

        $scopeMatcher = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('contao.routing.scope_matcher'))]);
        $isMethod = new Node\Identifier('BE' === $value->getValue() ? 'isBackendRequest' : 'isFrontendRequest');
        $result = new Node\Expr\MethodCall($scopeMatcher, $isMethod, [new Node\Arg($request)]);

        if ($node instanceof NotEqual || $node instanceof NotIdentical) {
            return new Node\Expr\BooleanNot($result);
        }

        return $result;
    }
}
