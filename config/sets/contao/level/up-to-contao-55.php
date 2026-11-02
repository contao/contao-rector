<?php

declare(strict_types=1);

use Contao\Rector\Set\ContaoLevelSetList;
use Contao\Rector\Set\ContaoSetList;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withSets([
        ContaoLevelSetList::UP_TO_CONTAO_53,
        LevelSetList::UP_TO_PHP_82,
    ])
;
