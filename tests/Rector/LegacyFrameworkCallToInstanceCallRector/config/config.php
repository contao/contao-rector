<?php

declare(strict_types=1);

use Contao\Controller;
use Contao\Database;
use Contao\Rector\Rector\LegacyFrameworkCallToInstanceCallRector;
use Contao\Rector\ValueObject\LegacyFrameworkCallToInstanceCall;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(LegacyFrameworkCallToInstanceCallRector::class, [
        new LegacyFrameworkCallToInstanceCall(Controller::class, 'getChildRecords', Database::class, 'getChildRecords'),
        new LegacyFrameworkCallToInstanceCall(Controller::class, 'getParentRecords', Database::class, 'getParentRecords'),
    ]);
};
