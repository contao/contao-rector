<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\System;
use PhpParser\Node;
use Rector\Rector\AbstractRector;

final class PageGlobalToPageFinderServiceRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Global_::class];
    }

    public function refactor(Node $node): Node|null
    {
        assert($node instanceof Node\Stmt\Global_);

        foreach ($node->vars as $var) {
            if (!$this->isName($var, 'objPage')) {
                continue;
            }

            $variable = new Node\Expr\Variable('objPage');

            $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified(System::class), 'getContainer');
            $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('contao.routing.page_finder'))]);
            $call = new Node\Expr\MethodCall($service, 'getCurrentPage');


            return new Node\Stmt\Expression(
                new Node\Expr\Assign(
                    $variable,
                    $call
                )
            );
        }

        return null;
    }
}
