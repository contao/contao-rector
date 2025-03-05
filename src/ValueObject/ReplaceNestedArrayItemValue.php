<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;

class ReplaceNestedArrayItemValue
{
    public function __construct(
        private readonly string $targetPath,
        private readonly mixed $oldValue,
        private readonly mixed $newValue,
    ) {
    }

    public function getTargetPath(): string
    {
        return $this->targetPath;
    }

    public function getOldValue(): mixed
    {
        return $this->oldValue;
    }

    public function getNewValue(): mixed
    {
        //return $this->newValue;
        return $this->getValueWithType($this->newValue);
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
