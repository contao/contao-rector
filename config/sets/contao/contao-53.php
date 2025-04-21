<?php

declare(strict_types=1);

use Contao\CoreBundle\File\ModelMetadataTrait;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Model\MetadataTrait;
use Contao\Rector\Rector\StringReplaceRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, [
        new RenameClassAndConstFetch(ContaoCorePermissions::class, 'USER_CAN_ACCESS_FORM', ContaoCorePermissions::class, 'USER_CAN_EDIT_FORM'),
    ]);

    $rectorConfig->ruleWithConfiguration(StringReplaceRector::class, [
        '_legend:hide}',
        '_legend:collapsed}'
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        ModelMetadataTrait::class => MetadataTrait::class
    ]);
};
