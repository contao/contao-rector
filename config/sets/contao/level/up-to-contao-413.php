<?php

declare(strict_types=1);

use Contao\Rector\Set\ContaoLevelSetList;
use Contao\Rector\Set\ContaoSetList;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withComposerBased(twig: true)
    ->withSets([
        ContaoLevelSetList::UP_TO_CONTAO_49,
        ContaoSetList::CONTAO_413,
        LevelSetList::UP_TO_PHP_74,
    ])
;
