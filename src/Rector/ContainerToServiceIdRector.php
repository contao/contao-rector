<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ContainerToServiceIdRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var array<string, string>
     */
    private array $serviceIds = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces renamed service IDs in ContainerInterface::get() calls', [
            new ConfiguredCodeSample(
                <<<'CODE_BEFORE'
                    $container->get('contao.opt-in');
                    CODE_BEFORE,
                <<<'CODE_AFTER'
                    $container->get('contao.opt_in');
                    CODE_AFTER,
                ['contao.opt-in' => 'contao.opt_in'],
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): Node|null
    {
        if (!$this->isName($node->name, 'get') || !$this->isObjectType($node->var, new ObjectType(ContainerInterface::class))) {
            return null;
        }

        $arg = $node->args[0] ?? null;

        if (!$arg instanceof Arg) {
            return null;
        }

        $serviceId = $this->getServiceId($arg);

        if (null === $serviceId || !isset($this->serviceIds[$serviceId])) {
            return null;
        }

        $arg->value = new String_($this->serviceIds[$serviceId]);

        return $node;
    }

    /**
     * @param array<string, string> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allString(array_keys($configuration));
        Assert::allString($configuration);
        $this->serviceIds = $configuration;
    }

    private function getServiceId(Arg $arg): string|null
    {
        if ($arg->value instanceof String_) {
            return $arg->value->value;
        }

        if ($arg->value instanceof ClassConstFetch && $this->isName($arg->value->name, 'class')) {
            return $this->getName($arg->value->class);
        }

        return null;
    }
}
