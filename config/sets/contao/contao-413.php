<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        // Added in Contao 4.10
        'scan' => 'Contao\Folder::scan',
        'ampersand' => 'Contao\StringUtil::ampersand',
        'array_insert' => 'Contao\ArrayUtil::arrayInsert',
        'array_is_assoc' => 'Contao\ArrayUtil::isAssoc',

        // see contao/contao#3530
        'utf8_chr' => 'mb_chr',
        'utf8_ord' => 'mb_ord',
        'utf8_strlen' => 'mb_strlen',
        'utf8_strpos' => 'mb_strpos',
        'utf8_strrchr' => 'mb_strrchr',
        'utf8_strrpos' => 'mb_strrpos',
        'utf8_strstr' => 'mb_strstr',
        'utf8_strtolower' => 'mb_strtolower',
        'utf8_strtoupper' => 'mb_strtoupper',
        'utf8_substr' => 'mb_substr',
        'utf8_str_split' => 'mb_str_split',
    ]);
};
