<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\Rector\ValueObject\ChangeDerivateClassReturnType;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;

final class ChangeDerivateClassReturnTypeRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<ChangeDerivateClassReturnType>
     */
    private array $configuration = [];

    public function configure(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): Node|null
    {
        assert($node instanceof Class_);

        if (!$node->extends instanceof Name)
        {
            return null;
        }

        foreach ($this->configuration as $config)
        {
            if (!$this->isName($node->extends, $config->getDerivate()))
            {
                continue;
            }

            $oldReturnType = $config->getOldReturnType();

            foreach ($node->getMethods() as $method)
            {
                if (
                    !$this->isName($method->name, $config->getMethod())
                    || $method->returnType->getType() !== $oldReturnType
                ) {
                    continue;
                }

                $method->returnType = $config->getNewReturnType();

                return $node;
            }
        }

        return null;
    }
}
