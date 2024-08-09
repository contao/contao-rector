<?php

declare(strict_types=1);

use Contao\CoreBundle\Twig\Extension\ContaoExtension;
use Contao\CoreBundle\Security\TwoFactor\BackupCodeManager;
use Contao\CoreBundle\Cron\Cron;
use Contao\CoreBundle\Image\Studio\FigureRenderer;
use Contao\CoreBundle\Routing\ResponseContext\CoreResponseContextFactory;
use Contao\ArrayUtil;
use Contao\BackendUser;
use Contao\Controller;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\CoreBundle\Util\SimpleTokenParser;
use Contao\Folder;
use Contao\Rector\Rector\ConstantToServiceCallRector;
use Contao\Rector\Rector\DataContainerConfigOptionToParameterRector;
use Contao\Rector\Rector\InsertTagsServiceRector;
use Contao\Rector\Rector\LegacyFrameworkCallToServiceCallRector;
use Contao\Rector\Rector\SystemLanguagesToServiceRector;
use Contao\Rector\ValueObject\ConstantToServiceCall;
use Contao\Rector\ValueObject\DataContainerConfigOptionToParameter;
use Contao\Rector\ValueObject\LegacyFrameworkCallToServiceCall;
use Contao\StringUtil;
use Patchwork\Utf8;
use Rector\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;
use Rector\Transform\ValueObject\StaticCallToFuncCall;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
    $rectorConfig->ruleWithConfiguration(DataContainerConfigOptionToParameterRector::class, [
        new DataContainerConfigOptionToParameter('validImageTypes', '%contao.image.valid_extensions%'),
    ]);

    // Contao 4.13
    $rectorConfig->rule(InsertTagsServiceRector::class);

    $rectorConfig->ruleWithConfiguration(ConstantToServiceCallRector::class, [
        new ConstantToServiceCall('REQUEST_TOKEN', 'contao.csrf.token_manager', 'getDefaultTokenValue'),
    ]);

    // Contao 4.13
    $rectorConfig->ruleWithConfiguration(ReplaceArgumentDefaultValueRector::class, [
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, 'contao.cache.clear_internal', 'contao.cache.clearer'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, 'contao.cache.warm_internal', 'contao.cache.warmer'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, 'contao.crawl.escargot_factory', 'contao.crawl.escargot.factory'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, 'contao.image.image_factory', 'contao.image.factory'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, 'contao.image.image_sizes', 'contao.image.sizes'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, 'contao.image.resizer', 'contao.image.legacy_resizer'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, 'contao.opt-in', 'contao.opt_in'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, Cron::class, 'contao.cron'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, ContaoFrameworkInterface::class, 'contao.framework'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, FigureRenderer::class, 'contao.image.studio.figure_renderer'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, CoreResponseContextFactory::class, 'contao.routing.response_context_factory'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, BackupCodeManager::class, 'contao.security.two_factor.backup_code_manager'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, ContaoExtension::class, 'contao.twig.extension'),
        new ReplaceArgumentDefaultValue(ContainerInterface::class, 'get', 0, SimpleTokenParser::class, 'contao.string.simple_token_parser'),
    ]);

    // Contao 4.12
    //'Contao\FrontendUser::isMemberOf($ids)' => 'Contao\System::getContainer()->get(\'security.helper\')->isGranted(ContaoCorePermissions::MEMBER_IN_GROUPS, $ids)',
};
