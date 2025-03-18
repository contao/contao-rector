<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Controller;
use PhpParser\Node;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ControllerMethodToVersionsClassRector extends AbstractLegacyFrameworkCallRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated Controller::createInitialVersion() and Controller::createNewVersion() to Versions class calls', [
            new CodeSample(
                <<<'CODE_BEFORE'
\Contao\Controller::createInitialVersion('tl_page', 17);
\Contao\Controller::createNewVersion('tl_page', 17);
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
(new \Contao\Versions('tl_page', 17))->initialize();
(new \Contao\Versions('tl_page', 17))->create();
CODE_AFTER
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        if ($this->isMethodCall($node, Controller::class, 'createInitialVersion')) {
            $versions = new Node\Expr\New_(new Node\Name\FullyQualified('Contao\Versions'), $node->args);
            $node = new Node\Expr\MethodCall($versions, 'initialize');

            return $node;
        }

        if ($this->isMethodCall($node, Controller::class, 'createNewVersion')) {
            $versions = new Node\Expr\New_(new Node\Name\FullyQualified('Contao\Versions'), $node->args);
            $node = new Node\Expr\MethodCall($versions, 'create');

            return $node;
        }

        return null;
    }
}
