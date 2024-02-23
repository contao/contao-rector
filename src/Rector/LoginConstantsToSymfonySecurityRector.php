<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\ConstFetch;
use PHPStan\Type\Constant\ConstantBooleanType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class LoginConstantsToSymfonySecurityRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated login constants to security service call', [
            new CodeSample(
                <<<'CODE_BEFORE'
$hasFrontendAccess = FE_USER_LOGGED_IN;
$hasBackendAccess = BE_USER_LOGGED_IN;
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$hasFrontendAccess = \Contao\System::getContainer()->get('security.helper')->isGranted('ROLE_MEMBER');
$hasBackendAccess = \Contao\System::getContainer()->get('contao.security.token_checker')->isPreviewMode();
CODE_AFTER
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [
            Equal::class,
            NotEqual::class,
            Identical::class,
            NotIdentical::class,
            ConstFetch::class,
        ];
    }

    public function refactor(Node $node): ?Node
    {
        assert([
            $node instanceof Equal
            || $node instanceof NotEqual
            || $node instanceof Identical
            || $node instanceof NotIdentical
            || $node instanceof ConstFetch
        ]);

        if ($node instanceof ConstFetch) {
            $value = $node;
            $compare = null;
        } elseif ($node instanceof BinaryOp && $this->nodeTypeResolver->getType($node->left) instanceof ConstantBooleanType) {
            $value = $node->right;
            $compare = $this->nodeTypeResolver->getType($node->left);
        } elseif ($node instanceof BinaryOp && $this->nodeTypeResolver->getType($node->right) instanceof ConstantBooleanType) {
            $value = $node->left;
            $compare = $this->nodeTypeResolver->getType($node->right);
        } else {
            return null;
        }

        if (!$value->name instanceof Node) {
            return null;
        }

        if ($this->isName($value->name, 'FE_USER_LOGGED_IN')) {
            $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Contao\System'), 'getContainer');
            $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('security.helper'))]);
            $call = new Node\Expr\MethodCall($service, new Node\Identifier('isGranted'), [new Node\Arg(new Node\Scalar\String_('ROLE_MEMBER'))]);

            return $this->handleBinaryOperation($node, $compare, $call);
        }

        if ($this->isName($value->name, 'BE_USER_LOGGED_IN')) {
            $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Contao\System'), 'getContainer');
            $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('contao.security.token_checker'))]);
            $call = new Node\Expr\MethodCall($service, new Node\Identifier('isPreviewMode'));

            return $this->handleBinaryOperation($node, $compare, $call);
        }

        return null;
    }

    private function handleBinaryOperation(Node $node, ConstantBooleanType|null $compare, Node\Expr\MethodCall $call): Node
    {
        if (!$compare) {
            return $call;
        }

        if (
            (($node instanceof NotEqual || $node instanceof NotIdentical) && true === $compare->getValue())
            || (($node instanceof Equal || $node instanceof Identical) && false === $compare->getValue())
        ) {
            return new Node\Expr\BooleanNot($call);
        }

        return $call;
    }
}
