<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Psr\Container\ContainerInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ContainerSessionToRequestStackSessionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rewrites session access to the request stack session', [
            new CodeSample(
                <<<'CODE_BEFORE'
\Contao\System::getContainer()->get('session');
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
\Contao\System::getContainer()->get('request_stack')->getSession();
CODE_AFTER
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if ($node->isFirstClassCallable()) {
            return null;
        }

        $args = $node->getArgs();

        if (
            !$this->isMethodCall($node, ContainerInterface::class, 'get')
            || 1 !== \count($args)
            || 'session' !== $args[0]->value->value
        ) {
            return null;
        }

        $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Contao\System'), 'getContainer');
        $requestStack = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('request_stack'))]);
        $node = new Node\Expr\MethodCall($requestStack, 'getSession');

        return $node;
    }

    private function isMethodCall(Node $node, string $className, string $methodName): bool
    {
        return $node instanceof Node\Expr\MethodCall
            && $this->isName($node->name, $methodName)
            && $this->isObjectType($node->var, new ObjectType($className))
        ;
    }
}
