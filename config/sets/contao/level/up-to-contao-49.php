<?php

declare(strict_types=1);

use Contao\Rector\Set\ContaoSetList;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withSets([
        ContaoSetList::CONTAO_49,
        ContaoSetList::FQCN,
        LevelSetList::UP_TO_PHP_72,
    ])
;
