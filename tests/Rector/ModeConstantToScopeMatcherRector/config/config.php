<?php

declare(strict_types=1);

use Contao\Rector\Rector\ModeConstantToScopeMatcherRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ModeConstantToScopeMatcherRector::class);
};
