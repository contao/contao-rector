<?php

declare(strict_types=1);

use Contao\Rector\Rector\SystemImageSizesToServiceRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SystemImageSizesToServiceRector::class);
};
