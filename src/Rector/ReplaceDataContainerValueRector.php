<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\DC_Table;
use Contao\Rector\ValueObject\ReplaceDataContainerValue;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Transform\ValueObject\StringToClassConstant;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ReplaceDataContainerValueRector extends AbstractRector implements ConfigurableRectorInterface
{
    private $parser;
    private $traverser;

    /**
     * @var array<ReplaceDataContainerValue>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ReplaceDataContainerValue::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces data container strings to class constants', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$GLOBALS['TL_DCA']['tl_foo'] = [
    'config' => [
        'dataContainer' => 'Table',
        //...
    ],
];
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$GLOBALS['TL_DCA']['tl_foo'] = [
    'config' => [
        'dataContainer' => \Contao\DC_Table::class,
        //...
    ],
];
CODE_AFTER,
                [new StringToClassConstant('Table', DC_Table::class, 'class')]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [
            ArrayDimFetch::class
        ];
    }

    public function refactor(Node $node): ?Node
    {
        assert(
            $node instanceof ArrayDimFetch
        );

        if (
            !$this->isName($node->var->var, 'GLOBALS')
            || 'TL_DCA' !== $node->var->dim->value
        ) {
            return null;
        }

        $name = $this->isName($node->var->var, 'GLOBALS');

        $parent = $node->getAttribute('parent');

        foreach ($this->configuration as $config) {

            if (!$node->key instanceof String_) {
                return null;
            }

            $arrayPath = explode('.', $config->getTargetPath());

            if (empty($arrayPath)) {
                continue;
            }

            $this->traverseArrayAndModifyValue($node->value, $arrayPath, $config);
        }

        return null;
    }

    private function traverseArrayAndModifyValue(Node $node, array $path, ReplaceDataContainerValue $config): void
    {
        if (
            empty($path)
            || !$node instanceof Node\Expr\Array_
        ) {
            return;
        }

        $keyValue = array_shift($path);

        $blnWildcard = ('*' === $keyValue);

        foreach ($node->items as $item) {
            if (
                $item->key instanceof String_
                && ($blnWildcard || $item->key->value === $keyValue)
            ) {
                // Handle 'fields' and skip directly into the ArrayItems
                if (
                    empty($path) && $item->value instanceof Node\Expr\Array_) {
                    $this->replaceDataContainerValue($item->value);
                } else {

                    $value = $item->value->value;
                    $configValue = $config->getOldValue();

                    $test = $this->isName($value, $configValue);

                    if ($test) {
                        $this->traverseArrayAndModifyValue($item->value, $path, $config);
                    }
                }
            }
        }
    }

    private function replaceDataContainerValue(Node &$value, ReplaceDataContainerValue $config): void
    {
        return;

        if ($value instanceof FuncCall && $this->isName($value->name, 'Config::get')) {
            $value = new String_('%contao.image.valid_extensions%');
        }
    }

    private function isGlobalDataContainerArrayValue(Node $node): bool
    {
        $parent = $node->left;

        return
            $parent instanceof ArrayItem
            && 'TL_DCA' === $parent->key->dim
            && $parent->key instanceof ArrayDimFetch
            && $this->isName($parent->key->var, 'GLOBALS')
            && $node instanceof ArrayItem
            && $node->key instanceof String_
            && $node->value instanceof Array_
        ;
    }

    private function isStaticCall(Node $node, string $className, string $methodName, string $option): bool
    {
        return $node instanceof StaticCall
            && $this->isName($node->name, $methodName)
            && $this->isObjectType($node->class, new ObjectType($className))
            && $option
            ;
    }
}

/*




            if (
                $node->value instanceof String_
                && $node->value->value === $config->getString()
            ) {
                $node->value = new Node\Expr\ClassConstFetch(
                    new Node\Name\FullyQualified($config->getClass()),
                    new Node\Identifier($config->getConstant())
                );

                return $node;
            }
 */
