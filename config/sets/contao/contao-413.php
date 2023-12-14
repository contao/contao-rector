<?php

declare(strict_types=1);

use Contao\ArrayUtil;
use Contao\BackendUser;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Folder;
use Contao\StringUtil;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(FuncCallToStaticCallRector::class, [
        // Contao 4.10
        new FuncCallToStaticCall('scan', Folder::class, 'scan'),
        new FuncCallToStaticCall('ampersand', StringUtil::class, 'ampersand'),
        new FuncCallToStaticCall('array_insert', ArrayUtil::class, 'arrayInsert'),
        new FuncCallToStaticCall('array_is_assoc', ArrayUtil::class, 'isAssoc'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
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

    $rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, [
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_EDIT_PAGE', ContaoCorePermissions::class, 'USER_CAN_EDIT_PAGE'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_EDIT_PAGE_HIERARCHY', ContaoCorePermissions::class, 'USER_CAN_EDIT_PAGE_HIERARCHY'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_DELETE_PAGE', ContaoCorePermissions::class, 'USER_CAN_DELETE_PAGE'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_EDIT_ARTICLES', ContaoCorePermissions::class, 'USER_CAN_EDIT_ARTICLES'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_EDIT_ARTICLE_HIERARCHY', ContaoCorePermissions::class, 'USER_CAN_EDIT_ARTICLE_HIERARCHY'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_DELETE_ARTICLES', ContaoCorePermissions::class, 'USER_CAN_DELETE_ARTICLES'),
    ]);
};
