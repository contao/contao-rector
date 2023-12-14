<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class LoginConstantsToSymfonySecurityRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated login constants to security service call', [
            new CodeSample(
                <<<'CODE_BEFORE'
$hasFrontendAccess = FE_USER_LOGGED_IN;
$hasBackendAccess = BE_USER_LOGGED_IN;
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$hasFrontendAccess = \Contao\System::getContainer()->get('security.helper')->isGranted('ROLE_MEMBER');
$hasBackendAccess = \Contao\System::getContainer()->get('contao.security.token_checker')->isPreviewMode();
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

        if ($this->isName($node->name, 'FE_USER_LOGGED_IN')) {
            $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Contao\System'), 'getContainer');
            $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('security.helper'))]);
            $node = new Node\Expr\MethodCall($service, new Node\Identifier('isGranted'), [new Node\Arg(new Node\Scalar\String_('ROLE_MEMBER'))]);

            return $node;
        }

        if ($this->isName($node->name, 'BE_USER_LOGGED_IN')) {
            $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Contao\System'), 'getContainer');
            $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('contao.security.token_checker'))]);
            $node = new Node\Expr\MethodCall($service, new Node\Identifier('isPreviewMode'));

            return $node;
        }

        return null;
    }
}
