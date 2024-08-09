<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class ConstantToServiceCall
{
    public function __construct(
        private readonly string $constant,
        private readonly string $serviceName,
        private readonly string $serviceMethodName
    ) {
    }

    public function getConstant(): string
    {
        return $this->constant;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getServiceMethodName(): string
    {
        return $this->serviceMethodName;
    }
}
