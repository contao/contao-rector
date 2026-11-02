<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Rector\ValueObject\LegacyFunctionToStaticCall;
use Contao\StringUtil;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class LegacyFunctionToStaticCallRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var LegacyFunctionToStaticCall[]
     */
    private array $configuration = [];

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, LegacyFunctionToStaticCall::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated legacy functions to static calls', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$data = deserialize($value, true);
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$data = \Contao\StringUtil::deserialize($value, true);
CODE_AFTER,
                [new LegacyFunctionToStaticCall('deserialize', StringUtil::class, 'deserialize')]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof FuncCall);

        foreach ($this->configuration as $config) {
            if ($this->isName($node, $config->getFunctionName())) {
                return new StaticCall(new FullyQualified($config->getClassName()), $config->getMethodName(), $node->args);
            }
        }

        return null;
    }
}
