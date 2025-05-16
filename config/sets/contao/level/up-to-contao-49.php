<?php

declare(strict_types=1);

use Contao\Rector\Set\ContaoSetList;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withSets([
        ContaoSetList::CONTAO_49,
        ContaoSetList::FQCN,
        LevelSetList::UP_TO_PHP_72,
        SymfonySetList::SYMFONY_30,
        SymfonySetList::SYMFONY_31,
        SymfonySetList::SYMFONY_32,
        SymfonySetList::SYMFONY_33,
        SymfonySetList::SYMFONY_34,
        SymfonySetList::SYMFONY_40,
        SymfonySetList::SYMFONY_41,
        SymfonySetList::SYMFONY_42,
        SymfonySetList::SYMFONY_43,
        SymfonySetList::SYMFONY_44,
    ])
;
