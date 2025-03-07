<?php

declare(strict_types=1);

use Contao\Rector\Rector\ConstantToServiceCallRector;
use Contao\Rector\ValueObject\ConstantToServiceCall;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ConstantToServiceCallRector::class, [
        new ConstantToServiceCall('REQUEST_TOKEN', 'contao.csrf.token_manager', 'getDefaultTokenValue'),

        new ConstantToServiceCall('TL_ASSETS_URL', 'contao.assets.assets_context', 'getStaticUrl'),
        new ConstantToServiceCall('TL_FILES_URL', 'contao.assets.files_context', 'getStaticUrl'),
    ]);
};
