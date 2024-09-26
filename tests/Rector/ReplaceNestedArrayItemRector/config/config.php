<?php

declare(strict_types=1);

use Contao\Rector\Rector\ReplaceNestedArrayItemRector;
use Contao\Rector\ValueObject\ReplaceNestedArrayItemValue;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReplaceNestedArrayItemRector::class, [
        new ReplaceNestedArrayItemValue('TL_DCA.*.*.dataContainer', 'Table', \Contao\DC_Table::class),
        new ReplaceNestedArrayItemValue('TL_DCA.*.config.dataContainer','Folder', \Contao\DC_Folder::class),
        new ReplaceNestedArrayItemValue('TL_DCA.tl_baz.config.dataContainer','File', \Contao\DC_File::class),

        new ReplaceNestedArrayItemValue(
            'TL_DCA.*.fields.*.eval.extensions',
            new StaticCall(new FullyQualified(Config::class), 'get', [new Arg(new String_('validImageTypes'))]),
            '%contao.image.valid_extensions%'
        ),
    ]);
};
