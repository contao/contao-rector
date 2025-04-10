<?php

declare(strict_types=1);

use Contao\DataContainer;
use Contao\Rector\Rector\ReplaceNestedArrayItemRector;
use Contao\Rector\ValueObject\ReplaceNestedArrayItemValue;
use Contao\System;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReplaceNestedArrayItemRector::class, [
        new ReplaceNestedArrayItemValue('TL_DCA.*.*.dataContainer', 'Table', \Contao\DC_Table::class),
        new ReplaceNestedArrayItemValue('TL_DCA.*.config.dataContainer','Folder', \Contao\DC_Folder::class),
        new ReplaceNestedArrayItemValue('TL_DCA.tl_bar.config.dataContainer','File', \Contao\DC_File::class),

        new ReplaceNestedArrayItemValue(
            'TL_DCA.*.fields.*.eval.extensions',
            new StaticCall(new FullyQualified(Config::class), 'get', [new Arg(new String_('validImageTypes'))]),
            '%contao.image.valid_extensions%'
        ),

        new ReplaceNestedArrayItemValue(
            'TL_DCA.*.fields.*.flag',
            1,
            new ClassConstFetch(new FullyQualified(DataContainer::class), 'SORT_INITIAL_LETTER_ASC')
        ),

        new ReplaceNestedArrayItemValue(
            'TL_DCA.*.fields.*.options',
            new StaticCall(new FullyQualified(System::class), 'getCountries'),
            new FuncCall(
                new Name('array_change_key_case'), [
                    new Arg(
                        new MethodCall(
                            new MethodCall(
                                new StaticCall(new FullyQualified(System::class), 'getContainer'),
                                'get',
                                [new Arg(new String_('contao.intl.countries'))]
                            ),
                            'getCountries'
                        )
                    )
                ]
            )
        )
    ]);
};
