<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Rector\ValueObject\LegacyFrameworkCallToStaticCall;
use PhpParser\Node;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class LegacyFrameworkCallToStaticCallRector extends AbstractLegacyFrameworkCallRector implements ConfigurableRectorInterface
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
            new CodeSample(
                <<<'CODE_BEFORE'
$html = \Contao\Controller::getImage($image, $width, $height);
$html = $this->getImage($image, $width, $height);
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$html = \Contao\Image::get($image, $width, $height);
$html = \Contao\Image::get($image, $width, $height);
CODE_AFTER
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
