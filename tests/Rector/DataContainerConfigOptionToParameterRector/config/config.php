<?php

declare(strict_types=1);

use Contao\Rector\Rector\DataContainerConfigOptionToParameterRector;
use Contao\Rector\ValueObject\DataContainerConfigOptionToParameter;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(DataContainerConfigOptionToParameterRector::class, [
        new DataContainerConfigOptionToParameter('validImageTypes', '%contao.image.valid_extensions%'),
    ]);
};
