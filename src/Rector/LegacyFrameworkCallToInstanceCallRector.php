<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Controller;
use Contao\Database;
use Contao\Rector\ValueObject\LegacyFrameworkCallToInstanceCall;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class LegacyFrameworkCallToInstanceCallRector extends AbstractLegacyFrameworkCallRector implements ConfigurableRectorInterface
{
    /**
     * @var LegacyFrameworkCallToInstanceCall[]
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, LegacyFrameworkCallToInstanceCall::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated legacy framework method to static calls', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$ids = \Contao\Controller::getChildRecords([42], 'tl_page');
$ids = \Contao\Controller::getParentRecords(42, 'tl_page');
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$ids = \Contao\Database::getInstance()->getChildRecords([42], 'tl_page');
$ids = \Contao\Database::getInstance()->getParentRecords(42, 'tl_page');
CODE_AFTER,
                [new LegacyFrameworkCallToInstanceCall(Controller::class, 'getChildRecords', Database::class, 'getChildRecords'),]
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall);

        foreach ($this->configuration as $config) {
            if ($this->isParentStaticOrMethodClassCall($node, $config->getOldClassName(), $config->getOldMethodName())) {
                $instance = new Node\Expr\StaticCall(new Node\Name\FullyQualified($config->getNewClassName()), 'getInstance');
                $node = new Node\Expr\MethodCall($instance, $config->getNewMethodName(), $node->args);

                return $node;
            }
        }

        return null;
    }
}
