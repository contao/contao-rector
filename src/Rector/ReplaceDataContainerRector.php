<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\DC_Table;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Transform\ValueObject\StringToClassConstant;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ReplaceDataContainerRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<StringToClassConstant>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, StringToClassConstant::class);
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
            ArrayItem::class
        ];
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof ArrayItem);

        if (
            !$node->key instanceof String_
            || 'dataContainer' !== $node->key->value
        ) {
            return null;
        }

        foreach ($this->configuration as $config) {

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
        }

        return null;
    }
}
