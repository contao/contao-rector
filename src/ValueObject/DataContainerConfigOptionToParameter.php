<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class DataContainerConfigOptionToParameter
{
    public function __construct(
        private readonly string $option,
        private readonly string $parameter,
    ) {
    }

    public function getOption(): string
    {
        return $this->option;
    }

    public function getParameter(): string
    {
        return $this->parameter;
    }
}
