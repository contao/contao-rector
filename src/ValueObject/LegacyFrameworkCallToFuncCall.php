<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class LegacyFrameworkCallToFuncCall
{
    public function __construct(
        private readonly string $oldClassName,
        private readonly string $oldMethodName,
        private readonly string $functionName,
    ) {
    }

    public function getOldClassName(): string
    {
        return $this->oldClassName;
    }

    public function getOldMethodName(): string
    {
        return $this->oldMethodName;
    }

    public function getFunctionName(): string
    {
        return $this->functionName;
    }
}
