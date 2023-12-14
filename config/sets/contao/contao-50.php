<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        // Added in Contao 4.10
        'nl2br_html5' => 'nl2br',
        'nl2br_xhtml' => 'nl2br',
    ]);

    // TODO: remove use of StringUtil::toXhtml
    // TODO: remove use of StringUtil::toHtml5
    // TODO: remove use of nl2br_pre
    // TODO: remove use of basename_natcasecmp
    // TODO: remove use of basename_natcasercmp
    // TODO: remove use of natcaseksort
    // TODO: remove use of length_sort_asc
    // TODO: remove use of length_sort_desc
    // TODO: remove use of array_duplicate
    // TODO: remove use of array_move_up
    // TODO: remove use of array_move_down
    // TODO: remove use of array_delete
    // TODO: remove use of utf8_decode_entities
    // TODO: remove use of utf8_chr_callback
    // TODO: remove use of utf8_hexchr_callback
    // TODO: remove use of utf8_detect_encoding
    // TODO: remove use of utf8_romanize

    // TODO: StringUtil::parseSimpleTokens should be replaced with contao.string.simple_token_parser service
    // TODO: DcaLoader::load() with the $blnNoCache parameter has been deprecated
};
