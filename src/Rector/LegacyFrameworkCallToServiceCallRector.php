<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Controller;
use Contao\Rector\ValueObject\LegacyFrameworkCallToServiceCall;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class LegacyFrameworkCallToServiceCallRector extends AbstractLegacyFrameworkCallRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var array<LegacyFrameworkCallToServiceCall>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, LegacyFrameworkCallToServiceCall::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated legacy framework method to service calls', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$buffer = $this->parseSimpleTokens($buffer, $arrTokens);
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$buffer = \Contao\System::getContainer()->get('contao.string.simple_token_parser')->parse($buffer, $arrTokens);
CODE_AFTER,
                [new LegacyFrameworkCallToServiceCall(Controller::class, 'parseSimpleTokens', 'contao.string.simple_token_parser', 'parse')]
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall);

        foreach ($this->configuration as $config) {
            if ($this->isParentStaticOrMethodClassCall($node, $config->getClassName(), $config->getMethodName())) {
                $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Contao\System'), 'getContainer');
                $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_($config->getServiceName()))]);
                $method_name = new Node\Identifier($config->getServiceMethodName());
                $node = new Node\Expr\MethodCall($service, $method_name, $node->args);

                return $node;
            }
        }

        return null;
    }
}
