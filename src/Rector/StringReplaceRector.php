<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class StringReplaceRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    private array $configuration = [];

    public function configure(array $configuration): void
    {
        Assert::allString($configuration);
        Assert::count($configuration, 2);
        Assert::uniqueValues($configuration);

        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces text occurrences within a string', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$foo = '{foo_legend},foo;{bar_legend:hide},bar;{baz_legend:hide},baz';
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$foo = '{foo_legend},foo;{bar_legend:collapsed},bar;{baz_legend:collapsed},baz';
CODE_AFTER,
                ['_legend:hide}', '_legend:collapsed}']
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof String_);

        if (false === strpos($node->value, $this->configuration[0])) {
            return null;
        }

        $newValue = str_replace($this->configuration[0], $this->configuration[1], $node->value);

        if ($node->value === $newValue) {
            return null;
        }

        return new String_($newValue);
    }
}
