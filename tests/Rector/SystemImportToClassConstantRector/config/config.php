<?php

declare(strict_types=1);

use Contao\BackendUser;
use Contao\Rector\Rector\SystemImportToClassConstantRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(SystemImportToClassConstantRector::class, [
        'BackendUser' => BackendUser::class,
    ]);
};
