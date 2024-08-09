<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Rector\ValueObject\ConstantToServiceCall;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ConstantToServiceCallRector extends AbstractLegacyFrameworkCallRector implements ConfigurableRectorInterface
{
    /**
     * @var array<ConstantToServiceCall>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ConstantToServiceCall::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated constants to service calls', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$requestToken = REQUEST_TOKEN;
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$requestToken = \Contao\System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();
CODE_AFTER,
                [new ConstantToServiceCall('REQUEST_TOKEN', 'contao.csrf.token_manager', 'getDefaultTokenValue')]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [
            Node\Expr\ConstFetch::class,
        ];
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\ConstFetch);

        foreach ($this->configuration as $config) {
            if ($this->isName($node->name, $config->getConstant())) {
                $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Contao\System'), 'getContainer');
                $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_($config->getServiceName()))]);
                $method_name = new Node\Identifier($config->getServiceMethodName());
                $node = new Node\Expr\MethodCall($service, $method_name);

                return $node;
            }
        }

        return null;
    }
}
