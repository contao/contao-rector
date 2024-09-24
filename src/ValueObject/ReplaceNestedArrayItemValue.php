<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class ReplaceNestedArrayItemValue
{
    public function __construct(
        private readonly string $targetPath,
        private readonly string $oldValue,
        private readonly string $newValue,
    ) {
    }

    public function getTargetPath(): string
    {
        return $this->targetPath;
    }

    public function getOldValue(): string
    {
        return $this->oldValue;
    }

    public function getNewValue(): string
    {
        return $this->newValue;
    }
}
