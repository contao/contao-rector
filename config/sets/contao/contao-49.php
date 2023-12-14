<?php

declare(strict_types=1);

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\StringUtil;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        // Deprecated in Contao 4.1
        Contao\CoreBundle\ContaoFrameworkInterface::class => ContaoFramework::class,

        // Deprecated in Contao 4.7
        Contao\CoreBundle\Framework\ContaoFrameworkInterface::class => ContaoFramework::class,
    ]);

    $rectorConfig->ruleWithConfiguration(FuncCallToStaticCallRector::class, [
        // Contao 4.1
        new FuncCallToStaticCall('utf8_convert_encoding', StringUtil::class, 'convertEncoding'),

        // Contao 4.2
        new FuncCallToStaticCall('deserialize', StringUtil::class, 'deserialize'),
        new FuncCallToStaticCall('specialchars', StringUtil::class, 'specialchars'),
        new FuncCallToStaticCall('trimsplit', StringUtil::class, 'trimsplit'),
        new FuncCallToStaticCall('standardize', StringUtil::class, 'standardize'),
        new FuncCallToStaticCall('strip_insert_tags', StringUtil::class, 'stripInsertTags'),
    ]);
};
