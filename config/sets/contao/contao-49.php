<?php

declare(strict_types=1);

use Contao\Automator;
use Contao\Backend;
use Contao\ContentElement;
use Contao\Controller;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\Database;
use Contao\Date;
use Contao\Environment;
use Contao\FileUpload;
use Contao\Folder;
use Contao\Image;
use Contao\Input;
use Contao\Module;
use Contao\Rector\Rector\ConstantToClassConstantRector;
use Contao\Rector\Rector\ConstantToServiceParameterRector;
use Contao\Rector\Rector\ControllerMethodToVersionsClassRector;
use Contao\Rector\Rector\LegacyFrameworkCallToFuncCallRector;
use Contao\Rector\Rector\LegacyFrameworkCallToInstanceCallRector;
use Contao\Rector\Rector\LegacyFrameworkCallToServiceCallRector;
use Contao\Rector\Rector\LegacyFrameworkCallToStaticCallRector;
use Contao\Rector\Rector\LegacyFunctionToStaticCallRector;
use Contao\Rector\Rector\LoginConstantsToSymfonySecurityRector;
use Contao\Rector\Rector\ModeConstantToScopeMatcherRector;
use Contao\Rector\Rector\SystemLogToMonologRector;
use Contao\Rector\ValueObject\ConstantToClassConstant;
use Contao\Rector\ValueObject\ConstantToServiceParameter;
use Contao\Rector\ValueObject\LegacyFrameworkCallToFuncCall;
use Contao\Rector\ValueObject\LegacyFrameworkCallToInstanceCall;
use Contao\Rector\ValueObject\LegacyFrameworkCallToServiceCall;
use Contao\Rector\ValueObject\LegacyFrameworkCallToStaticCall;
use Contao\Rector\ValueObject\LegacyFunctionToStaticCall;
use Contao\StringUtil;
use Contao\System;
use Contao\Widget;
use Rector\Config\RectorConfig;
use Rector\Removing\Rector\Class_\RemoveInterfacesRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        // Deprecated in Contao 4.1
        \Contao\CoreBundle\ContaoFrameworkInterface::class => ContaoFramework::class,

        // Deprecated in Contao 4.7
        \Contao\CoreBundle\Framework\ContaoFrameworkInterface::class => ContaoFramework::class,
    ]);

    $rectorConfig->ruleWithConfiguration(LegacyFunctionToStaticCallRector::class, [
        // Contao 4.1
        new LegacyFunctionToStaticCall('utf8_convert_encoding', StringUtil::class, 'convertEncoding'),

        // Contao 4.2
        new LegacyFunctionToStaticCall('deserialize', StringUtil::class, 'deserialize'),
        new LegacyFunctionToStaticCall('specialchars', StringUtil::class, 'specialchars'),
        new LegacyFunctionToStaticCall('trimsplit', StringUtil::class, 'trimsplit'),
        new LegacyFunctionToStaticCall('standardize', StringUtil::class, 'standardize'),
        new LegacyFunctionToStaticCall('strip_insert_tags', StringUtil::class, 'stripInsertTags'),
    ]);

    $rectorConfig->ruleWithConfiguration(LegacyFrameworkCallToStaticCallRector::class, [
        // Contao 4.0
        new LegacyFrameworkCallToStaticCall(Controller::class, 'getTheme', Backend::class, 'getTheme'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'getBackendThemes', Backend::class, 'getThemes'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'removeOldFeeds', Automator::class, 'purgeXmlFiles'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'restoreBasicEntities', StringUtil::class, 'restoreBasicEntities'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'resizeImage', Image::class, 'resize'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'getImage', Image::class, 'get'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'generateImage', Image::class, 'getHtml'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'prepareForWidget', Widget::class, 'getAttributesFromDca'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'optionSelected', Widget::class, 'optionSelected'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'optionChecked', Widget::class, 'optionChecked'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'findContentElement', ContentElement::class, 'findClass'),
        new LegacyFrameworkCallToStaticCall(Controller::class, 'findFrontendModule', Module::class, 'findClass'),
        new LegacyFrameworkCallToStaticCall(System::class, 'parseDate', Date::class, 'parse'),
    ]);

    $rectorConfig->ruleWithConfiguration(LegacyFrameworkCallToFuncCallRector::class, [
        // Contao 4.0
        new LegacyFrameworkCallToFuncCall(Controller::class, 'classFileExists', 'class_exists')
    ]);

    $rectorConfig->ruleWithConfiguration(LegacyFrameworkCallToStaticCallRector::class, [
        // Contao 4.0
        new LegacyFrameworkCallToStaticCall(Environment::class, 'get', Environment::class, 'get'),
        new LegacyFrameworkCallToStaticCall(Environment::class, 'set', Environment::class, 'set'),
        new LegacyFrameworkCallToStaticCall(Input::class, 'get', Input::class, 'get'),
        new LegacyFrameworkCallToStaticCall(Input::class, 'post', Input::class, 'post'),
        new LegacyFrameworkCallToStaticCall(Input::class, 'postHtml', Input::class, 'postHtml'),
        new LegacyFrameworkCallToStaticCall(Input::class, 'postRaw', Input::class, 'postRaw'),

        // Contao 4.6
        new LegacyFrameworkCallToStaticCall(FileUpload::class, 'getMaximumUploadSize', FileUpload::class, 'getMaxUploadSize'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        // Contao 4.0
        new MethodCallRename(Folder::class, 'clear', 'purge'),
        new MethodCallRename(Database::class, 'executeCached', 'execute'),
    ]);

    $rectorConfig->ruleWithConfiguration(ConstantToServiceParameterRector::class, [
        new ConstantToServiceParameter('TL_ROOT', 'kernel.project_dir'),
    ]);

    // Contao 4.1
    $rectorConfig->ruleWithConfiguration(LegacyFrameworkCallToServiceCallRector::class, [
        new LegacyFrameworkCallToServiceCall(System::class, 'getImageSizes', 'contao.image.sizes', 'getAllOptions'),
    ]);

    // Contao 4.2
    $rectorConfig->ruleWithConfiguration(ConstantToClassConstantRector::class, [
        new ConstantToClassConstant('TL_ERROR', ContaoContext::class, 'ERROR'),
        new ConstantToClassConstant('TL_ACCESS', ContaoContext::class, 'ACCESS'),
        new ConstantToClassConstant('TL_GENERAL', ContaoContext::class, 'GENERAL'),
        new ConstantToClassConstant('TL_FILES', ContaoContext::class, 'FILES'),
        new ConstantToClassConstant('TL_CRON', ContaoContext::class, 'CRON'),
        new ConstantToClassConstant('TL_FORMS', ContaoContext::class, 'FORMS'),
        new ConstantToClassConstant('TL_EMAIL', ContaoContext::class, 'EMAIL'),
        new ConstantToClassConstant('TL_CONFIGURATION', ContaoContext::class, 'CONFIGURATION'),
        new ConstantToClassConstant('TL_NEWSLETTER', ContaoContext::class, 'NEWSLETTER'),
        new ConstantToClassConstant('TL_REPOSITORY', ContaoContext::class, 'REPOSITORY'),
    ]);

    $rectorConfig->ruleWithConfiguration(LegacyFrameworkCallToInstanceCallRector::class, [
        new LegacyFrameworkCallToInstanceCall(Controller::class, 'getChildRecords', Database::class, 'getChildRecords'),
        new LegacyFrameworkCallToInstanceCall(Controller::class, 'getParentRecords', Database::class, 'getParentRecords'),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveInterfacesRector::class, [
        ServiceAnnotationInterface::class,
    ]);

    $rectorConfig->rule(LoginConstantsToSymfonySecurityRector::class);
    $rectorConfig->rule(ControllerMethodToVersionsClassRector::class);
    $rectorConfig->rule(ModeConstantToScopeMatcherRector::class);
    $rectorConfig->rule(SystemLogToMonologRector::class);

    //'Contao\Controller::getBackendLanguages()' => 'Contao\System::getContainer()->get(\'contao.intl.locales\')->getEnabledLocales(null, true)',
};
