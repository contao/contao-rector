<?php

declare(strict_types=1);

use Contao\Backend;
use Contao\Controller;
use Contao\Input;
use Contao\Rector\Rector\LegacyFrameworkCallToStaticCallRector;
use Contao\Rector\ValueObject\LegacyFrameworkCallToStaticCall;
use Contao\StringUtil;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(LegacyFrameworkCallToStaticCallRector::class, [
        new LegacyFrameworkCallToStaticCall(Controller::class, 'getTheme', Backend::class, 'getTheme'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'restoreBasicEntities', StringUtil::class, 'restoreBasicEntities'),
        new LegacyFrameworkCallToStaticCall(Input::class, 'get', Input::class, 'get'),
    ]);
};
