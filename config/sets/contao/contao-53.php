<?php

declare(strict_types=1);

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, [
        new RenameClassAndConstFetch(ContaoCorePermissions::class, 'USER_CAN_ACCESS_FORM', ContaoCorePermissions::class, 'USER_CAN_EDIT_FORM'),
    ]);
};
