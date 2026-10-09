<?php

declare(strict_types=1);

use Contao\Rector\Set\ContaoLevelSetList;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withSets([
        ContaoLevelSetList::UP_TO_CONTAO_55,
        LevelSetList::UP_TO_PHP_83,
    ])
;
