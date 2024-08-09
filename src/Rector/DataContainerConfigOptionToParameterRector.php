<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Config;
use Contao\Rector\ValueObject\DataContainerConfigOptionToParameter;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class DataContainerConfigOptionToParameterRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<DataContainerConfigOptionToParameter>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, DataContainerConfigOptionToParameter::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Converts deprecated config options to parameters', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$GLOBALS['TL_DCA']['tl_foo']['fields'] = [
    'bar' => [
        'exclude' => true,
        'inputType' => 'fileTree',
        'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=>Contao\Config::get('validImageTypes')],
        'sql' => "binary(16) NULL"
    ],
];
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$GLOBALS['TL_DCA']['tl_foo']['fields'] = [
    'bar' => [
        'exclude' => true,
        'inputType' => 'fileTree',
        'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=>'%contao.image.valid_extensions%'],
        'sql' => "binary(16) NULL"
    ],
];
CODE_AFTER,
                [new DataContainerConfigOptionToParameter('validImageTypes', '%contao.image.valid_extensions%')]
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
        if (
            !$node->key instanceof String_
            || 'eval' !== $node->key->value
            || !$node->value instanceof Node\Expr\Array_
        ) {
            return null;
        }

        foreach ($node->value->items as $eval) {
            if (
                $eval->key instanceof String_
                && $eval->key->value === 'extensions'
                && $eval->value instanceof StaticCall
            ) {
                foreach ($this->configuration as $config) {
                    if ($this->isStaticCall($eval->value, Config::class, 'get', $config->getOption())) {
                        $eval->value = new String_($config->getParameter());

                        return $node;
                    }
                }
            }
        }

        return null;
    }

    protected function isStaticCall(Node $node, string $className, string $methodName, string $option): bool
    {
        return $node instanceof StaticCall
            && $this->isName($node->name, $methodName)
            && $this->isObjectType($node->class, new ObjectType($className))
            && $option
        ;
    }
}
