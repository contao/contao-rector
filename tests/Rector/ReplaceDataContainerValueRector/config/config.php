<?php

declare(strict_types=1);

use Contao\DC_File;
use Contao\DC_Folder;
use Contao\DC_Table;
use Contao\Rector\Rector\ReplaceDataContainerValueRector;
use Contao\Rector\ValueObject\ReplaceDataContainerValue;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReplaceDataContainerValueRector::class, [
        new ReplaceDataContainerValue('config.dataContainer','Table', DC_Table::class),
        new ReplaceDataContainerValue('config.dataContainer','File', DC_File::class),
        new ReplaceDataContainerValue('config.dataContainer','Folder', DC_Folder::class)
    ]);
};
