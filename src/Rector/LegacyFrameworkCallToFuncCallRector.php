<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Controller;
use Contao\Rector\ValueObject\LegacyFrameworkCallToFuncCall;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class LegacyFrameworkCallToFuncCallRector extends AbstractLegacyFrameworkCallRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var LegacyFrameworkCallToFuncCall[]
     */
    private array $configuration = [];

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, LegacyFrameworkCallToFuncCall::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated legacy framework methods to function calls', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$exists = \Contao\Controller::classFileExists($class);
$exists = $this->classFileExists($class);
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$exists = class_exists($class);
$exists = class_exists($class);
CODE_AFTER,
                [new LegacyFrameworkCallToFuncCall(Controller::class, 'classFileExists', 'class_exists')]
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall);

        foreach ($this->configuration as $config) {
            if ($this->isParentStaticOrMethodClassCall($node, $config->getOldClassName(), $config->getOldMethodName())) {
                return new Node\Expr\FuncCall(new Node\Name($config->getFunctionName()), $node->args);
            }
        }

        return null;
    }
}
