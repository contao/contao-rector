<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Controller;
use Contao\InsertTags;
use PhpParser\Node;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class InsertTagsServiceRector extends AbstractLegacyFrameworkCallRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated Controller::replaceInsertTags() to service calls', [
            new CodeSample(
                <<<'CODE_BEFORE'
$buffer = \Contao\Controller::replaceInsertTags($buffer);
$uncached = \Contao\Controller::replaceInsertTags($buffer, false);
$class = (new \Contao\InsertTags())->replace($buffer);
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$buffer = \Contao\System::getContainer('contao.insert_tags.parser')->replace($buffer);
$uncached = \Contao\System::getContainer('contao.insert_tags.parser')->replaceInline($buffer);
$class = \Contao\System::getContainer('contao.insert_tags.parser')->replace($buffer);
CODE_AFTER
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall);

        if (
            !$this->isMethodCall($node, InsertTags::class, 'replace')
            && !$this->isParentStaticOrMethodClassCall($node, Controller::class, 'replaceInsertTags')
        ) {
            return null;
        }

        $cached = true;

        if (\count($node->args) > 1) {
            $cached = (bool) ($node->args[1]->value->value ?? 'false' !== $node->args[1]->value->name->getFirst());
        }

        $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified('Contao\System'), 'getContainer');
        $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('contao.insert_tag.parser'))]);
        $method_name = new Node\Identifier($cached ? 'replace' : 'replaceInline');
        $node = new Node\Expr\MethodCall($service, $method_name, [$node->args[0]]);

        return $node;
    }
}
