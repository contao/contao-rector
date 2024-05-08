<?php

declare(strict_types=1);

use Contao\Rector\Rector\ConfigOptionToParameterRector;
use Contao\Rector\ValueObject\ConfigOptionToParameter;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ConfigOptionToParameterRector::class, [
        new ConfigOptionToParameter('validImageTypes', '%contao.image.valid_extensions%'),
    ]);
};
