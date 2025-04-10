<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Config;
use Contao\Rector\ValueObject\ReplaceNestedArrayItemValue;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ReplaceNestedArrayItemRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    const PATH_END = '__end__';

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
            'Replaces array item values based on a configuration with wild card support and strict types',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_BEFORE'
$GLOBALS['TL_DCA']['tl_foo']['config']['dataContainer'] = 'Table';
$GLOBALS['TL_DCA']['tl_foo']['foo']['bar']['baz'] = 'TYPOlight';
$GLOBALS['TL_DCA']['tl_complex'] = [
    'config' => [],
    'fields' => [
        'screenshot' => [
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=> Config::get('validImageTypes')],
            'sql' => "binary(16) NULL"
        ],
    ]
];
CODE_BEFORE
                    ,
                    <<<'CODE_AFTER'
$GLOBALS['TL_DCA']['tl_foo']['config']['dataContainer'] = \Contao\DC_Table::class;
$GLOBALS['TL_DCA']['tl_foo']['foo']['bar']['baz'] = 'Contao';
$GLOBALS['TL_DCA']['tl_complex'] = [
    'config' => [],
    'fields' => [
        'screenshot' => [
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=> '%contao.image.valid_extensions%'],
            'sql' => "binary(16) NULL"
        ],
    ]
];

CODE_AFTER
                    ,
                    [
                        new ReplaceNestedArrayItemValue('TL_DCA.*.config.dataContainer', 'Table', \Contao\DC_Table::class),
                        new ReplaceNestedArrayItemValue('TL_DCA.*.foo.*.baz', 'TYPOlight', 'Contao'),
                        new ReplaceNestedArrayItemValue(
                            'TL_DCA.*.fields.*.eval.extensions',
                            new StaticCall(new FullyQualified(Config::class), 'get', [new Arg(new String_('validImageTypes'))]),
                            '%contao.image.valid_extensions%'
                        ),
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
            $childTraversal = $this->createChildTraversalPath($node->expr);

            foreach ($this->configuration as $configuration)
            {
                $targetPath = explode('.', $configuration->getTargetPath());

                $childrenKeyPaths = $this->matchPaths($targetPath, $arrParentKeyPath, $childTraversal);

                if ([] !== $childrenKeyPaths)
                {
                    $oldValue = $configuration->getOldValue();
                    $newValue = $configuration->getNewValue();

                    foreach ($childrenKeyPaths as $childrenKeyPath)
                    {
                        $this->replaceTargetNodeValue($node, $childrenKeyPath, $configuration, $oldValue, $newValue);
                    }
                }
            }
        }

        return null;
    }

    /**
     * This function matches the paths based on the left assignment aka $parentPath and the right assignment which may
     * be a multidimensional array ($childTraversal).
     *
     * On success, will return an array of paths to traverse down for manipulation
     * On failure, will return an empty array
     */
    private function matchPaths(array $targetPath, array $parentPath, array|string $childTraversalPath): array
    {
        $matches = [];

        // Early return because we are already at the end of the path
        if (self::PATH_END === $childTraversalPath)
        {
            // Array length differs thus not a real match
            if (count($targetPath) !== count($parentPath))
            {
                return [];
            }

            // Match against the target path
            foreach ($targetPath as $index => $value)
            {
                if ('*' !== $value && $value !== $parentPath[$index])
                {
                    return [];
                }
            }

            return [$parentPath];
        }

        $this->findMatches($targetPath, $parentPath, $childTraversalPath, [], $matches);

        return $matches;
    }

    private function findMatches(array $targetPath, array $parentPath, array|string $childTraversalPath, array $currentPath, array &$matches): void
    {
        // Store a valid match at the end of the path
        if ($childTraversalPath === self::PATH_END)
        {
            $matches[] = $currentPath;
            return;
        }

        if (empty($targetPath) || !is_array($childTraversalPath))
        {
            return;
        }

        $value = array_shift($targetPath);

        // Remove parent paths and traverse down
        if (
            [] !== $parentPath
            && ($value === '*' || $value === reset($parentPath))
        ) {
            array_shift($parentPath);

            $this->findMatches(
                $targetPath,
                $parentPath,
                $childTraversalPath,
                $currentPath,
                $matches
            );

            return;
        }

        // Wildcard support for array key traversing
        if ('*' === $value)
        {
            foreach ($childTraversalPath as $key => $subPath)
            {
                $this->findMatches(
                    $targetPath,
                    $parentPath,
                    $subPath,
                    array_merge($currentPath, [$key]),
                    $matches
                );
            }

            return;
        }

        // Normal traversal
        if (isset($childTraversalPath[$value]))
        {
            $this->findMatches(
                $targetPath,
                $parentPath,
                $childTraversalPath[$value],
                array_merge($currentPath, [$value]),
                $matches
            );
        }
    }

    private function normalizeNode(Node &$node): void
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(
            new class extends NodeVisitorAbstract
            {
                public function enterNode(Node $node): void
                {
                    $node->setAttributes([]);
                }
            }
        );

        $node = $traverser->traverse([$node])[0];
    }

    private function matchesReplacementValue(mixed $item, mixed $old): bool
    {
        $current = $item->value ?? $item;

        $currentType = gettype($current);
        $oldType = gettype($old);

        // If we are looking for a node, we want to normalize it because we can't mock the proper attributes...
        if ($current instanceof Node && $old instanceof Node)
        {
            $this->normalizeNode($current);
            $this->normalizeNode($old);

            $currentType = $current->getType();
            $oldType = $old->getType();
        }
        return
            $currentType === $oldType
            && $current == $old
        ;
    }

    /**
     * Checks for the found target node and replaces the node if it matches.
     * This runs recursively till it either replaces the whole node whilst traversing down the nodes using the
     * childrenKeyPath from the matchPaths function until it confirms the type of the oldValue replacing the new one.
     */
    private function replaceTargetNodeValue(mixed $node, array $childrenKeyPath, ReplaceNestedArrayItemValue $configuration, mixed $oldValue, mixed $newValue): void
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

        if ($item instanceof Array_)
        {
            foreach ($item->items as &$sub)
            {
                if (
                    $sub instanceof ArrayItem
                    && $sub->key instanceof String_
                ) {
                    if (
                        [] !== $childrenKeyPath
                        && $sub->key->value === array_values($childrenKeyPath)[0] ?? null
                    ) {
                        array_shift($childrenKeyPath);
                        $this->replaceTargetNodeValue($sub, $childrenKeyPath, $configuration, $oldValue, $newValue);
                    }

                    elseif (
                        [] === $childrenKeyPath
                        && $sub->key->value === array_values($childrenKeyPath)[0] ?? null
                        && $this->matchesReplacementValue($sub, $oldValue)
                    ) {
                        $sub = $newValue;
                        return;
                    }
                }
            }
        }
        elseif ($this->matchesReplacementValue($item, $oldValue))
        {
            $item = $newValue;
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
     * The final child is always the constant PATH_END :)
     */
    private function createChildTraversalPath(Node $expr, array &$keys = []): array|string
    {
        // We are already at the end
        if (!$expr instanceof Array_)
        {
            return self::PATH_END;
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
                $this->createChildTraversalPath($item->value, $keys[$key]);
            }
            else
            {
                $keys[$key] = self::PATH_END;
            }
        }

        return $keys;
    }
}
