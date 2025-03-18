<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\Rector\ValueObject\ConstantToClassConstant;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ConstantToClassConstantRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var array<ConstantToClassConstant>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ConstantToClassConstant::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated constants to class constants', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$logLevel = TL_ACCESS;
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$logLevel = \Contao\CoreBundle\Monolog\ContaoContext::ACCESS;
CODE_AFTER,
                [new ConstantToClassConstant('TL_ERROR', ContaoContext::class, 'ERROR')]
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
            if ($this->isName($node->name, $config->getOldConstant())) {
                return new Node\Expr\ClassConstFetch(new Node\Name\FullyQualified($config->getNewClass()), new Node\Identifier($config->getNewConstant()));
            }
        }

        return null;
    }
}
