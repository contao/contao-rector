<?php

declare(strict_types=1);

use Contao\ArrayUtil;
use Contao\BackendUser;
use Contao\Controller;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DC_Table;
use Contao\Folder;
use Contao\Rector\Rector\ConstantToServiceCallRector;
use Contao\Rector\Rector\InsertTagsServiceRector;
use Contao\Rector\Rector\LegacyFrameworkCallToServiceCallRector;
use Contao\Rector\Rector\SystemLanguagesToServiceRector;
use Contao\Rector\ValueObject\ConstantToServiceCall;
use Contao\Rector\ValueObject\LegacyFrameworkCallToServiceCall;
use Contao\StringUtil;
use Patchwork\Utf8;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Rector\Transform\Rector\String_\StringToClassConstantRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;
use Rector\Transform\ValueObject\StaticCallToFuncCall;
use Rector\Transform\ValueObject\StringToClassConstant;

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

    $rectorConfig->ruleWithConfiguration(StaticCallToFuncCallRector::class, [
        new StaticCallToFuncCall(Utf8::class, 'strtoupper', 'mb_strtoupper'),
        new StaticCallToFuncCall(Utf8::class, 'mb_strtolower', 'mb_strtolower'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, [
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_EDIT_PAGE', ContaoCorePermissions::class, 'USER_CAN_EDIT_PAGE'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_EDIT_PAGE_HIERARCHY', ContaoCorePermissions::class, 'USER_CAN_EDIT_PAGE_HIERARCHY'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_DELETE_PAGE', ContaoCorePermissions::class, 'USER_CAN_DELETE_PAGE'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_EDIT_ARTICLES', ContaoCorePermissions::class, 'USER_CAN_EDIT_ARTICLES'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_EDIT_ARTICLE_HIERARCHY', ContaoCorePermissions::class, 'USER_CAN_EDIT_ARTICLE_HIERARCHY'),
        new RenameClassAndConstFetch(BackendUser::class, 'CAN_DELETE_ARTICLES', ContaoCorePermissions::class, 'USER_CAN_DELETE_ARTICLES'),
    ]);

    $rectorConfig->ruleWithConfiguration(LegacyFrameworkCallToServiceCallRector::class, [
        // Contao 4.10
        new LegacyFrameworkCallToServiceCall(Controller::class, 'parseSimpleTokens', 'contao.string.simple_token_parser', 'parse'),
        new LegacyFrameworkCallToServiceCall(StringUtil::class, 'parseSimpleTokens', 'contao.string.simple_token_parser', 'parse'),
    ]);

    // Contao 4.12
    $rectorConfig->rule(SystemLanguagesToServiceRector::class);

    // Contao 4.13
    $rectorConfig->rule(InsertTagsServiceRector::class);
    $rectorConfig->ruleWithConfiguration(StringToClassConstantRector::class, [
        new StringToClassConstant('Table', DC_Table::class, 'class'),
    ]);

    $rectorConfig->ruleWithConfiguration(ConstantToServiceCallRector::class, [
        new ConstantToServiceCall('REQUEST_TOKEN', 'contao.csrf.token_manager', 'getDefaultTokenValue'),
    ]);

    // Contao 4.12
    //'Contao\FrontendUser::isMemberOf($ids)' => 'Contao\System::getContainer()->get(\'security.helper\')->isGranted(ContaoCorePermissions::MEMBER_IN_GROUPS, $ids)',
};
