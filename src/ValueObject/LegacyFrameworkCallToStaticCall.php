<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class LegacyFrameworkCallToStaticCall
{
    public function __construct(
        private readonly string $oldClassName,
        private readonly string $oldMethodName,
        private readonly string $newClassName,
        private readonly string $newMethodName
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

    public function getNewClassName(): string
    {
        return $this->newClassName;
    }

    public function getNewMethodName(): string
    {
        return $this->newMethodName;
    }
}
