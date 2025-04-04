<?php

declare (strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Rector\ValueObject\RemoveMethodCall;
use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Webmozart\Assert\Assert;

class RemoveMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var list<RemoveMethodCall>
     */
    private array $remove = [];

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [
            Node\Expr\StaticCall::class,
            Node\Expr\MethodCall::class,
        ];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node) : Node|null
    {
        if (!$node instanceof Node\Expr\StaticCall && !$node instanceof Node\Expr\MethodCall) {
            return null;
        }

        foreach ($this->remove as $remove) {
            if (
                !$this->isName($node->name, $remove->getMethod())
                || ($node instanceof Node\Expr\StaticCall && $this->getName($node->class) !== $remove->getClass())
                || ($node instanceof Node\Expr\MethodCall && !$this->isObjectType($node->var, new ObjectType($remove->getClass())))
            ) {
                continue;
            }

            return $node->args[$remove->getArgument()]->value;
        }

        return null;
    }

    /**
     * @param list<array{0: string, 1: string}> $configuration
     */
    public function configure(array $configuration) : void
    {
        Assert::allIsInstanceOf($configuration, RemoveMethodCall::class);
        $this->remove = $configuration;
    }
}
