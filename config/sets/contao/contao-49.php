<?php

declare(strict_types=1);

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        // Deprecated in Contao 4.1
        ContaoFrameworkInterface::class => ContaoFramework::class,
    ]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        // Added in Contao 4.1
        'utf8_convert_encoding' => 'Contao\StringUtil::convertEncoding',

        // Added in Contao 4.2
        'deserialize' => 'Contao\StringUtil::deserialize',
        'specialchars' => 'Contao\StringUtil::specialchars',
        'trimsplit' => 'Contao\StringUtil::trimsplit',
        'standardize' => 'Contao\StringUtil::standardize',
        'strip_insert_tags' => 'Contao\StringUtil::stripInsertTags',
    ]);
};
