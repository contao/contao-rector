<?php

declare(strict_types=1);

use Contao\Controller;
use Contao\Rector\Rector\LegacyFrameworkCallToFuncCallRector;
use Contao\Rector\ValueObject\LegacyFrameworkCallToFuncCall;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(LegacyFrameworkCallToFuncCallRector::class, [
        new LegacyFrameworkCallToFuncCall(Controller::class, 'classFileExists', 'class_exists'),
    ]);
};
