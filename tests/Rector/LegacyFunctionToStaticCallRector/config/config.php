<?php

declare(strict_types=1);

use Contao\Rector\Rector\LegacyFunctionToStaticCallRector;
use Contao\Rector\ValueObject\LegacyFunctionToStaticCall;
use Contao\StringUtil;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(LegacyFunctionToStaticCallRector::class, [
        new LegacyFunctionToStaticCall('deserialize', StringUtil::class, 'deserialize'),
    ]);
};
