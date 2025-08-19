<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Validation\RectorAssert;
use Webmozart\Assert\Assert;

class ModifyArguments
{
    public function __construct(
        private readonly string $class,
        private readonly string $method,
        private array $arguments,
    ) {
        RectorAssert::className($class);
        RectorAssert::methodName($method);
        Assert::isArray($arguments);

        /** @var int|string $parameter */
        foreach ($arguments as $parameter => $argument)
        {
            $this->arguments[$parameter] = $this->getValueWithType($argument);
        }
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    private function getValueWithType(mixed $newValue): mixed
    {
        // Methods
        if (is_callable($newValue))
        {
            return $newValue();
        }

        if (is_string($newValue))
        {
            // Class const
            if (class_exists($newValue))
            {
                return new ClassConstFetch(new FullyQualified($newValue), 'class');
            }

            return new String_($newValue);
        }

        return $newValue;
    }
}
