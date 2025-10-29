<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

use PhpParser\Node;

class ChangeDerivateClassReturnType
{
    public function __construct(
        private readonly string $derivate,
        private readonly string $method,
        private readonly string $oldReturnType,
        private readonly mixed $newReturnType,
    ) {
    }

    public function getDerivate(): string
    {
        return $this->derivate;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getOldReturnType(): string
    {
        return $this->oldReturnType;
    }

    public function getNewReturnType(): Node|null
    {
        return $this->newReturnType;
    }
}
