<?php

declare(strict_types=1);

use Contao\Rector\Rector\StringReplaceRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(\Contao\Rector\Rector\RemoveMethodCallRector::class, [
        new \Contao\Rector\ValueObject\RemoveMethodCall(\Contao\StringUtil::class, 'toHtml5'),
    ]);
};
