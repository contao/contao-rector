<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Config;
use Contao\Input;
use Contao\Rector\ValueObject\ModifyArguments;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ModifyArgumentsRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var array<ModifyArguments>
     */
    private array $configuration;

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ModifyArguments::class);
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Adds arguments to a static method call', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
\Contao\Input::stripTags(null, '');
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
\Contao\Input::stripTags(null, '', \Contao\Config::get('allowedAttributes'));
CODE_AFTER,
                [new ModifyArguments(Input::class, 'stripTags', [2 => new StaticCall(new FullyQualified(Config::class), 'get', [new Arg(new String_('allowedAttributes'))])])]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->configuration as $configuration)
        {
            if (
                !$this->isName($node->class, $configuration->getClass())
                || !$this->isName($node->name, $configuration->getMethod())
            ) {
                continue;
            }

            foreach ($configuration->getArguments() as $parameter => $argument)
            {
                if (is_int($parameter))
                {
                    $node->args[$parameter] = new Arg($argument);
                }
                elseif (is_string($parameter))
                {
                    /** @var Arg $value */
                    foreach ($node->args as $key => $value)
                    {
                        if ($this->getName($value) === $parameter)
                        {
                            $node->args[$key]->value = $argument;
                            break 2;
                        }
                    }

                    $node->args[] = new Arg($argument, false, false, [], new Node\Identifier($parameter));
                }
            }
        }

        return $node;
    }
}
