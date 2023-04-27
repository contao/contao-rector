<?php

declare(strict_types=1);

use Contao\Rector\Set\ContaoLevelSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        ContaoLevelSetList::UP_TO_CONTAO_50,
    ]);
};
