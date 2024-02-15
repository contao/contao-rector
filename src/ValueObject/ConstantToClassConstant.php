<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class ConstantToClassConstant
{
    public function __construct(
        private readonly string $oldConstant,
        private readonly string $newClass,
        private readonly string $newConstant,
    ) {
    }

    public function getOldConstant(): string
    {
        return $this->oldConstant;
    }

    public function getNewClass(): string
    {
        return $this->newClass;
    }

    public function getNewConstant(): string
    {
        return $this->newConstant;
    }
}
