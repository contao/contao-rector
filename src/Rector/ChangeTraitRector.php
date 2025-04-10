<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use PHPParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\TraitUse;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Webmozart\Assert\Assert;

final class ChangeTraitRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<string, string>
     */
    private array $traits = [];

    public function getNodeTypes() : array
    {
        return [
            TraitUse::class,
        ];
    }

    public function refactor(Node $node): Node|null
    {
        assert($node instanceof TraitUse);

        foreach ($node->traits as $key => $trait)
        {
            if (isset($this->traits[$this->getName($trait)]))
            {
                $node->traits[$key] = new FullyQualified($this->traits[$this->getName($trait)]);
            }
        }

        return $node;
    }

    public function configure(array $configuration): void
    {
        Assert::allString(array_keys($configuration), FullyQualified::class);
        Assert::allString($configuration, FullyQualified::class);

        $this->traits = $configuration;
    }
}
