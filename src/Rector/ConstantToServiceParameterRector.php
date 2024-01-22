<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Rector\ValueObject\ConstantToServiceParameter;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ConstantToServiceParameterRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<ConstantToServiceParameter>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ConstantToServiceParameter::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated constants to service parameters', [
            new CodeSample(
                <<<'CODE_BEFORE'
$projectDir = TL_ROOT;
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$projectDir = \Contao\System::getContainer()->getParameter('kernel.project_dir');
CODE_AFTER
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
                $node = new Node\Expr\MethodCall($container, 'getParameter', [new Node\Arg(new Node\Scalar\String_($config->getParameter()))]);

                return $node;
            }
        }

        return null;
    }
}
