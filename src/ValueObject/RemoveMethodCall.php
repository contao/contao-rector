<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class RemoveMethodCall
{
    public function __construct(
        private readonly string $class,
        private readonly string $method,
        private readonly int $argument = 0,
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getArgument(): int
    {
        return $this->argument;
    }
}
