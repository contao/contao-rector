<?php

declare(strict_types=1);

use Contao\Rector\Rector\SystemLogToMonologRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SystemLogToMonologRector::class);
};
