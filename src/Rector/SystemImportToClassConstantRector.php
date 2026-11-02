<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\System;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class SystemImportToClassConstantRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var array<string, class-string>
     */
    private array $classes = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces legacy class names in System::import() calls with class constants', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
                    $this->import('BackendUser', 'User');
                    CODE_BEFORE,
                <<<'CODE_AFTER'
                    $this->import(\Contao\BackendUser::class, 'User');
                    CODE_AFTER,
                ['BackendUser' => 'Contao\BackendUser'],
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): Node|null
    {
        $caller = $node instanceof MethodCall ? $node->var : $node->class;

        if (!$this->isName($node->name, 'import') || !$this->isObjectType($caller, new ObjectType(System::class))) {
            return null;
        }

        $arg = $node->args[0] ?? null;

        if (!$arg instanceof Arg || !$arg->value instanceof String_ || !isset($this->classes[$arg->value->value])) {
            return null;
        }

        $arg->value = new ClassConstFetch(new FullyQualified($this->classes[$arg->value->value]), 'class');

        return $node;
    }

    /**
     * @param array<string, class-string> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allString(array_keys($configuration));
        Assert::allString($configuration);
        $this->classes = $configuration;
    }
}
