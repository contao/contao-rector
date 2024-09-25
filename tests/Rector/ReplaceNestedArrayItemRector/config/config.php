<?php

declare(strict_types=1);

use Contao\DC_File;
use Contao\DC_Folder;
use Contao\DC_Table;
use Contao\Rector\Rector\ReplaceNestedArrayItemRector;
use Contao\Rector\ValueObject\ReplaceNestedArrayItemValue;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReplaceNestedArrayItemRector::class, [
        new ReplaceNestedArrayItemValue('TL_DCA.*.*.dataContainer','Table', DC_Table::class),
        new ReplaceNestedArrayItemValue('TL_DCA.*.config.dataContainer','File', DC_File::class),
        new ReplaceNestedArrayItemValue('TL_DCA.tl_baz.config.dataContainer','Folder', DC_Folder::class)
    ]);
};
