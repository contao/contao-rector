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
    const PATH_MATCHES = '__hit__';

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
$GLOBALS['TL_DCA']['tl_foo']['foo']['bar']['baz'] = 'TYPOlight';
CODE_BEFORE
                    ,
                    <<<'CODE_AFTER'
$GLOBALS['TL_DCA']['tl_foo']['config']['dataContainer'] = \Contao\DC_Table::class;
$GLOBALS['TL_DCA']['tl_foo']['foo']['bar']['baz'] = 'Contao';
CODE_AFTER
                    ,
                    [
                        new ReplaceNestedArrayItemValue('config.dataContainer', 'Table', \Contao\DC_Table::class),
                        new ReplaceNestedArrayItemValue('foo.bar.baz', 'TYPOlight', 'Contao'),
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
            $arrParentKeyPath = $this->findParentKeys($node->var);
            $arrChildTraversalPath = $this->createChildrenTraversePath($node->expr);

            foreach ($this->configuration as $configuration)
            {
                $targetPath = explode('.', $configuration->getTargetPath());

                $childrenKeyPath = $this->matchPaths($targetPath, $arrParentKeyPath, $arrChildTraversalPath);

                // $childrenKeyPath is false if it never matched a path, otherwise it's always an array
                if (false !== $childrenKeyPath)
                {
                    $this->replaceTargetNodeValue($node, $childrenKeyPath, $configuration);
                }
            }
        }

        return null;
    }

    /**
     * This function matches the paths based on the left assignment aka $parentPath and the right assignment which may
     * be a multidimensional array ($childTraversal).
     *
     * On success, will return the path to traverse down for manipulation
     * On failure, will return false
     */
    private function matchPaths(array $targetPath, array &$parentPath, array|string $childTraversalPath): array|false
    {
        $childrenKeyPath = [];

        // Early return cause the value already matches the right hand assignment
        if (self::PATH_MATCHES === $childTraversalPath)
        {
            return $childrenKeyPath;
        }

        foreach ($targetPath as $key => $value)
        {
            // Remove parent paths and traverse down
            if (
                [] !== $parentPath
                && ($value === '*' || $value === $parentPath[$key] ?? null)
            ) {
                unset($parentPath[$key]);
                unset($targetPath[$key]);
            }

            // Wildcard support for array key traversing
            elseif (
                $value === '*'
                && [] !== $childTraversalPath
            ) {
                $waypoint = array_keys($childTraversalPath)[0];

                // Assuming it's a wildcard, we actually want to store the first key we find
                $childTraversalPath = $childTraversalPath[$waypoint];
                $childrenKeyPath[] = $waypoint;
            }

            elseif (isset($childTraversalPath[$value]))
            {
                $childrenKeyPath[] = $value;
                $childTraversalPath = $childTraversalPath[$value] ?? [];

                // Early return if the target did match
                if (self::PATH_MATCHES === $childTraversalPath)
                {
                    return $childrenKeyPath;
                }
            }

            // This only ever happens if we never had a childTraversalPath in the first place such as
            // $GLOBALS['TL_DCA']['tl_baz']['config']['dataContainer'] = 'Folder';
            elseif ([] === $childTraversalPath)
            {
                return $childrenKeyPath;
            }
        }

        return false;
    }

    private function matchesReplacementValue($item, $old): bool
    {
        return
            gettype($item?->value) === gettype($old)
            && $item?->value === $old
        ;
    }

    /**
     * Checks for the found target node and replaces the node if it matches.
     * This runs recursively till it either replaces the whole node whilst traversing down the nodes using the
     * childrenKeyPath from the matchPaths function until it confirms the type of the oldValue replacing the new one.
     *
     * Hint: The new values within ReplaceNestedArrayItemValue already have the nodes prepared so new ones have to be
     * added there.
     */
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
        $newValue = $configuration->getNewValue();

        if ($this->matchesReplacementValue($item, $oldValue))
        {
            $item = $newValue;
        }
        elseif ($item instanceof Array_)
        {
            foreach ($item->items as &$sub)
            {
                if (
                    $sub instanceof ArrayItem
                    && $sub->key instanceof String_
                ) {
                    if ($sub->key->value === array_values($childrenKeyPath)[0] ?? null)
                    {
                        array_shift($childrenKeyPath);
                        $this->replaceTargetNodeValue($sub, $childrenKeyPath, $configuration);
                    }

                    elseif (
                        [] === $childrenKeyPath
                        && $this->matchesReplacementValue($sub, $oldValue)
                    ) {
                        $sub = $newValue;
                        return;
                    }
                }
            }
        }

    }

    /**
     * Returns a simple array with the parent keys in the descending order
     */
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

    /**
     * Converts the child array items into a multidimensional array so we can validate the traversal path.
     * The array keys are the valid array keys and the values are the children.
     * The final child is always the constant PATH_MATCHES :)
     */
    private function createChildrenTraversePath(Node $expr, array &$keys = []): array
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

            $key = $item->key->value ?? $item->value->value ?? null;

            if (null === $key)
            {
                continue;
            }

            if (
                $item->value instanceof Array_
                || $item->value instanceof ArrayItem
            ) {
                $keys[$key] = [];
                $this->createChildrenTraversePath($item->value, $keys[$key]);
            }
            else
            {
                $keys[$key] = self::PATH_MATCHES;
            }
        }

        return $keys;
    }
}
