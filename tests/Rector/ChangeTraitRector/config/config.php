<?php

declare(strict_types=1);

use Contao\CoreBundle\File\ModelMetadataTrait;
use Contao\Model\MetadataTrait;
use Contao\Rector\Rector\ChangeTraitRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void
{
    $rectorConfig->ruleWithConfiguration(ChangeTraitRector::class, [
        ModelMetadataTrait::class => MetadataTrait::class
    ]);
};
