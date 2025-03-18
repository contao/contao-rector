<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Backend;
use Contao\Controller;
use Contao\Rector\ValueObject\LegacyFrameworkCallToStaticCall;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class LegacyFrameworkCallToStaticCallRector extends AbstractLegacyFrameworkCallRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var LegacyFrameworkCallToStaticCall[]
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, LegacyFrameworkCallToStaticCall::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated legacy framework method to static calls', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
$html = \Contao\Controller::getImage($image, $width, $height);
$html = $this->getImage($image, $width, $height);
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$html = \Contao\Image::get($image, $width, $height);
$html = \Contao\Image::get($image, $width, $height);
CODE_AFTER,
                [new LegacyFrameworkCallToStaticCall(Controller::class, 'getTheme', Backend::class, 'getTheme')]
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall);

        foreach ($this->configuration as $config) {
            if ($this->isParentStaticOrMethodClassCall($node, $config->getOldClassName(), $config->getOldMethodName(), $config->getNewClassName(), $config->getNewMethodName())) {
                return new Node\Expr\StaticCall(
                    new Node\Name\FullyQualified($config->getNewClassName()),
                    $config->getNewMethodName(),
                    $node->args
                );
            }
        }

        return null;
    }
}
