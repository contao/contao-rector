<?php

declare(strict_types=1);

use Contao\Rector\Set\ContaoLevelSetList;
use Contao\Rector\Set\ContaoSetList;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withComposerBased(twig: true, doctrine: true, symfony: true)
    ->withSets([
        ContaoLevelSetList::UP_TO_CONTAO_49,
        ContaoSetList::CONTAO_413,
        LevelSetList::UP_TO_PHP_74,
        SymfonySetList::SYMFONY_50,
        SymfonySetList::SYMFONY_50_TYPES,
        SymfonySetList::SYMFONY_51,
        SymfonySetList::SYMFONY_52,
        SymfonySetList::SYMFONY_53,
        SymfonySetList::SYMFONY_54,
    ])
;
