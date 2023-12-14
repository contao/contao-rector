<?php

declare(strict_types=1);

namespace Contao\Rector\ValueObject;

class LegacyFrameworkCallToServiceCall
{
    public function __construct(
        private readonly string $className,
        private readonly string $methodName,
        private readonly string $serviceName,
        private readonly string $serviceMethodName
    ) {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
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
