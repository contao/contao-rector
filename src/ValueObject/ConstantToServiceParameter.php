<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class ConstantToServiceParameter
{
    public function __construct(
        private readonly string $constant,
        private readonly string $parameter,
    ) {
    }

    public function getConstant(): string
    {
        return $this->constant;
    }

    public function getParameter(): string
    {
        return $this->parameter;
    }
}
