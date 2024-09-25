<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Rector\ValueObject\ReplaceNestedArrayItemValue;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ReplaceNestedArrayItemRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<ReplaceNestedArrayItemValue>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ReplaceNestedArrayItemValue::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replaces array values based on configuration',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_BEFORE'
$GLOBALS['TL_DCA']['tl_foo']['config']['dataContainer'] = 'Table';
$GLOBALS['TL_DCA']['tl_foo']['foo']['bar']['baz'] = 'Table';
CODE_BEFORE
                    ,
                    <<<'CODE_AFTER'
$GLOBALS['TL_DCA']['tl_foo']['config']['dataContainer'] = \Contao\DC_Table::class;
$GLOBALS['TL_DCA']['tl_foo']['foo']['bar']['baz'] = \Contao\DC_Table::class;
CODE_AFTER
                    ,
                    [
                        new ReplaceNestedArrayItemValue('config.dataContainer', 'Table', '\Contao\DC_Table::class'),
                        new ReplaceNestedArrayItemValue('foo.bar.baz', 'Table', '\Contao\DC_Table::class'),
                    ]
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [
            Assign::class
        ];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Assign)
        {
            return null;
        }

        if ($node->var instanceof ArrayDimFetch)
        {
            $parentKeyPath = $this->findParentKeys($node->var);
            $childrenKeyPath = $this->findChildrenKeys($node->expr);

            foreach ($this->configuration as $configuration)
            {
                $targetPath = explode('.', $configuration->getTargetPath());

                if (
                    $this->matchPaths($targetPath, [...$parentKeyPath, ...$childrenKeyPath])
                ) {
                    $this->replaceTargetNodeValue($node, $childrenKeyPath, $configuration);
                }
            }
        }

        return null;
    }

    private function matchPaths(array $targetPath, array $currentPath): bool
    {
        if (count($targetPath) !== count($currentPath))
        {
            return false;
        }

        foreach ($targetPath as $key => $value)
        {
            if ($value === '*' || $value === $currentPath[$key] ?? null)
            {
                continue;
            }

            return false;
        }

        return true;
    }

    private function matchesReplacementValue($item, $old): bool
    {
        return
            gettype($item?->value) === gettype($old)
            && $item?->value === $old
        ;
    }

    private function replaceTargetNodeValue(Assign|ArrayItem $node, array $childrenKeyPath, ReplaceNestedArrayItemValue $configuration): void
    {
        if (isset($node->expr))
        {
            $item = &$node->expr;
        }
        elseif (isset($node->value))
        {
            $item = &$node->value;
        }
        else
        {
            return;
        }

        $oldValue = $configuration->getOldValue();

        if ($this->matchesReplacementValue($item, $oldValue))
        {
            $item = $configuration->getNewValue();
        }
        elseif ($item instanceof Array_)
        {
            foreach ($item->items as $sub)
            {
                if (
                    $sub instanceof ArrayItem
                    && $sub->key instanceof String_
                    && $sub->key->value === array_values($childrenKeyPath)[0] ?? null
                ) {
                    array_shift($childrenKeyPath);
                    $this->replaceTargetNodeValue($sub, $childrenKeyPath, $configuration);
                }
            }
        }
    }

    private function findParentKeys(ArrayDimFetch $arrayDimFetch): array
    {
        $keys = [];

        while ($arrayDimFetch instanceof ArrayDimFetch)
        {
            if ($arrayDimFetch->dim instanceof String_)
            {
                $keys[] = $arrayDimFetch->dim->value;
            }

            $arrayDimFetch = $arrayDimFetch->var;
        }

        return array_reverse($keys);
    }

    private function findChildrenKeys(Node $expr): array
    {
        if (!$expr instanceof Array_)
        {
            return [];
        }

        $keys = [];

        foreach ($expr->items as $item)
        {
            if (!$item instanceof ArrayItem)
            {
                continue;
            }

            if ($item->key instanceof String_)
            {
                $keys[] = $item->key->value;
            }

            if ($item->value instanceof Array_)
            {
                $keys = array_merge($keys, $this->findChildrenKeys($item->value));
            }
        }

        return $keys;
    }
}
