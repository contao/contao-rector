<?php

declare(strict_types=1);

use Contao\Rector\Set\ContaoSetList;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonyLevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        ContaoSetList::CONTAO_49,
        ContaoSetList::FQCN,
        LevelSetList::UP_TO_PHP_72,
        DoctrineSetList::DOCTRINE_DBAL_211,
        SymfonyLevelSetList::UP_TO_SYMFONY_44,
    ]);
};
