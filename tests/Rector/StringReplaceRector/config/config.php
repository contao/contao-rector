<?php

declare(strict_types=1);

use Contao\Rector\Rector\StringReplaceRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {

    $rectorConfig->ruleWithConfiguration(StringReplaceRector::class, [
        '_legend:hide}',
        '_legend:collapsed}'
    ]);
};
