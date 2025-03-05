<?php

declare(strict_types=1);

use Contao\DataContainer;
use Contao\Rector\Rector\ReplaceNestedArrayItemRector;
use Contao\Rector\ValueObject\ReplaceNestedArrayItemValue;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReplaceNestedArrayItemRector::class, [
        new ReplaceNestedArrayItemValue('TL_DCA.*.list.sorting.flag', 13, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_INITIAL_LETTER_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.list.sorting.flag', 14, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_INITIAL_LETTERS_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.list.sorting.flag', 15, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_DAY_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.list.sorting.flag', 16, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_MONTH_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.list.sorting.flag', 17, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_YEAR_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.list.sorting.flag', 18, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_BOTH')),

        new ReplaceNestedArrayItemValue('TL_DCA.*.fields.*.flag', 13, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_INITIAL_LETTER_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.fields.*.flag', 14, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_INITIAL_LETTERS_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.fields.*.flag', 15, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_DAY_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.fields.*.flag', 16, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_MONTH_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.fields.*.flag', 17, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_YEAR_BOTH')),
        new ReplaceNestedArrayItemValue('TL_DCA.*.fields.*.flag', 18, new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_BOTH')),
    ]);
};
