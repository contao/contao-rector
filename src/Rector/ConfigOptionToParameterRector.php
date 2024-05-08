<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Config;
use Contao\Rector\ValueObject\ConfigOptionToParameter;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ConfigOptionToParameterRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<ConfigOptionToParameter>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ConfigOptionToParameter::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated config options to parameters', [
            new CodeSample(
                <<<'CODE_BEFORE'
$extensions = \Contao\Config::get('validImageTypes');
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$extensions = '%contao.image.valid_extensions%';
CODE_AFTER
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [
            Node\Expr\StaticCall::class,
            Node\Expr\MethodCall::class,
        ];
    }

    public function refactor(Node $node): ?Node
    {
        foreach ($this->configuration as $config) {
            if ($this->isStaticCall($node, Config::class, 'get', $config->getOption())) {
                return new Node\Scalar\String_($config->getParameter());
            }
        }

        return null;
    }

    protected function isStaticCall(Node $node, string $className, string $methodName, string $option): bool
    {
        return $node instanceof Node\Expr\StaticCall
            && $this->isName($node->name, $methodName)
            && $this->isObjectType($node->class, new ObjectType($className))
            && $option
        ;
    }
}
