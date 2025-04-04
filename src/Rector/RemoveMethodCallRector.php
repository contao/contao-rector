<?php

declare (strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Webmozart\Assert\Assert;

class RemoveMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var list<array{0: string, 1: string}>
     */
    private array $removedMethods = [];

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node) : ?int
    {
        $assign = $node->expr;

        if (!$assign instanceof Node\Expr\Assign) {
            return null;
        }

        $expr = $assign->expr;

        if (!$expr instanceof Node\Expr\StaticCall) {
            return null;
        }

        foreach ($this->removedMethods as $removed) {
            if (!$this->isName($expr->name, $removed[1]) || $this->getName($expr->class) !== $removed[0]) {
                continue;
            }

            $assign->expr = $expr->args[0]->value;
        }

        return null;
    }

    /**
     * @param list<array{0: string, 1: string}> $configuration
     */
    public function configure(array $configuration) : void
    {
        Assert::allIsArray($configuration);
        $this->removedMethods = $configuration;
    }
}
