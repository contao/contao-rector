<?php

declare(strict_types=1);

use Contao\DC_File;
use Contao\DC_Folder;
use Contao\DC_Table;
use Contao\Rector\Rector\ReplaceDataContainerRector;
use Rector\Config\RectorConfig;
use Rector\Transform\ValueObject\StringToClassConstant;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReplaceDataContainerRector::class, [
        new StringToClassConstant('Table', DC_Table::class, 'class'),
        new StringToClassConstant('File', DC_File::class, 'class'),
        new StringToClassConstant('Folder', DC_Folder::class, 'class')
    ]);
};
