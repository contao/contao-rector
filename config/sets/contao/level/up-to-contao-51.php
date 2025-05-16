<?php

declare(strict_types=1);

use Contao\Rector\Set\ContaoLevelSetList;
use Contao\Rector\Set\ContaoSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([
        ContaoLevelSetList::UP_TO_CONTAO_50,
        ContaoSetList::CONTAO_51,
    ])
;
