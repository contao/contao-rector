<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class LegacyFunctionToStaticCall
{
    public function __construct(
        private readonly string $functionName,
        private readonly string $className,
        private readonly string $methodName,
    ) {
    }

    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }
}
