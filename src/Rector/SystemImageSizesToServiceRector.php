<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\System;
use PhpParser\Node;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SystemImageSizesToServiceRector extends AbstractLegacyFrameworkCallRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated \Contao\System::getImageSizes() method to service call', [
            new CodeSample(
                <<<'CODE_BEFORE'
$sizes = \Contao\System::getImageSizes();
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$sizes = \Contao\System::getContainer()->get('contao.image.sizes')->getAllOptions();
CODE_AFTER
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall);

        if ($this->isParentStaticOrMethodClassCall($node, System::class, 'getImageSizes')) {
            $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified(System::class), 'getContainer');
            $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('contao.image.sizes'))]);
            $method_name = new Node\Identifier('getAllOptions');
            $node = new Node\Expr\MethodCall($service, $method_name, $node->args);

            return $node;
        }

        return null;
    }
}
