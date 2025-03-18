<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\System;
use PhpParser\Node;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SystemLanguagesToServiceRector extends AbstractLegacyFrameworkCallRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated \Contao\System::getLanguages() method to service call', [
            new CodeSample(
                <<<'CODE_BEFORE'
$languaegs = \Contao\System::getLanguages();
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$languages = \Contao\System::getContainer()->get('contao.intl.locales')->getLocales(null, true);
CODE_AFTER
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall);

        if ($this->isParentStaticOrMethodClassCall($node, System::class, 'getLanguages')) {
            $arg = $node->getArgs()[0] ?? null;

            if (!$arg || !$this->getType($arg->value)->isTrue()->yes()) {
                $method_name = new Node\Identifier('getLocales');
            } else {
                $method_name = new Node\Identifier('getEnabledLocales');
            }

            $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified(System::class), 'getContainer');
            $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('contao.intl.locales'))]);
            $node = new Node\Expr\MethodCall($service, $method_name, $this->nodeFactory->createArgs([null, true]));

            return $node;
        }

        return null;
    }
}
