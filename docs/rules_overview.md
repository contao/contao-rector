# 15 Rules Overview

## ConstantToClassConstantRector

Fixes deprecated constants to class constants

:wrench: **configure it!**

- class: [`Contao\Rector\Rector\ConstantToClassConstantRector`](../src/Rector/ConstantToClassConstantRector.php)

```diff
-$logLevel = TL_ACCESS;
+$logLevel = \Contao\CoreBundle\Monolog\ContaoContext::ACCESS;
```

<br>

## ConstantToServiceCallRector

Fixes deprecated constants to service calls

:wrench: **configure it!**

- class: [`Contao\Rector\Rector\ConstantToServiceCallRector`](../src/Rector/ConstantToServiceCallRector.php)

```diff
-$requestToken = REQUEST_TOKEN;
+$requestToken = \Contao\System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();
```

<br>

## ConstantToServiceParameterRector

Fixes deprecated constants to service parameters

:wrench: **configure it!**

- class: [`Contao\Rector\Rector\ConstantToServiceParameterRector`](../src/Rector/ConstantToServiceParameterRector.php)

```diff
-$projectDir = TL_ROOT;
+$projectDir = \Contao\System::getContainer()->getParameter('kernel.project_dir');
```

<br>

## ContainerSessionToRequestStackSessionRector

Rewrites session access to the request stack session

- class: [`Contao\Rector\Rector\ContainerSessionToRequestStackSessionRector`](../src/Rector/ContainerSessionToRequestStackSessionRector.php)

```diff
-\Contao\System::getContainer()->get('session');
+\Contao\System::getContainer()->get('request_stack')->getSession();
```

<br>

## ControllerMethodToVersionsClassRector

Fixes deprecated `Controller::createInitialVersion()` and `Controller::createNewVersion()` to Versions class calls

- class: [`Contao\Rector\Rector\ControllerMethodToVersionsClassRector`](../src/Rector/ControllerMethodToVersionsClassRector.php)

```diff
-\Contao\Controller::createInitialVersion('tl_page', 17);
-\Contao\Controller::createNewVersion('tl_page', 17);
+(new \Contao\Versions('tl_page', 17))->initialize();
+(new \Contao\Versions('tl_page', 17))->create();
```

<br>

## InsertTagsServiceRector

Fixes deprecated `Controller::replaceInsertTags()` to service calls

- class: [`Contao\Rector\Rector\InsertTagsServiceRector`](../src/Rector/InsertTagsServiceRector.php)

```diff
-$buffer = \Contao\Controller::replaceInsertTags($buffer);
-$uncached = \Contao\Controller::replaceInsertTags($buffer, false);
-$class = (new \Contao\InsertTags())->replace($buffer);
+$buffer = \Contao\System::getContainer('contao.insert_tags.parser')->replace($buffer);
+$uncached = \Contao\System::getContainer('contao.insert_tags.parser')->replaceInline($buffer);
+$class = \Contao\System::getContainer('contao.insert_tags.parser')->replace($buffer);
```

<br>

## LegacyFrameworkCallToInstanceCallRector

Fixes deprecated legacy framework method to static calls

:wrench: **configure it!**

- class: [`Contao\Rector\Rector\LegacyFrameworkCallToInstanceCallRector`](../src/Rector/LegacyFrameworkCallToInstanceCallRector.php)

```diff
-$ids = \Contao\Controller::getChildRecords([42], 'tl_page');
-$ids = \Contao\Controller::getParentRecords(42, 'tl_page');
+$ids = \Contao\Database::getInstance()->getChildRecords([42], 'tl_page');
+$ids = \Contao\Database::getInstance()->getParentRecords(42, 'tl_page');
```

<br>

## LegacyFrameworkCallToServiceCallRector

Fixes deprecated legacy framework method to service calls

:wrench: **configure it!**

- class: [`Contao\Rector\Rector\LegacyFrameworkCallToServiceCallRector`](../src/Rector/LegacyFrameworkCallToServiceCallRector.php)

```diff
-$buffer = $this->parseSimpleTokens($buffer, $arrTokens);
+$buffer = \Contao\System::getContainer()->get('contao.string.simple_token_parser')->parse($buffer, $arrTokens);
```

<br>

## LegacyFrameworkCallToStaticCallRector

Fixes deprecated legacy framework method to static calls

:wrench: **configure it!**

- class: [`Contao\Rector\Rector\LegacyFrameworkCallToStaticCallRector`](../src/Rector/LegacyFrameworkCallToStaticCallRector.php)

```diff
-$html = \Contao\Controller::getImage($image, $width, $height);
-$html = $this->getImage($image, $width, $height);
+$html = \Contao\Image::get($image, $width, $height);
+$html = \Contao\Image::get($image, $width, $height);
```

<br>

## LoginConstantsToSymfonySecurityRector

Fixes deprecated login constants to security service call

- class: [`Contao\Rector\Rector\LoginConstantsToSymfonySecurityRector`](../src/Rector/LoginConstantsToSymfonySecurityRector.php)

```diff
-$hasFrontendAccess = FE_USER_LOGGED_IN;
-$hasBackendAccess = BE_USER_LOGGED_IN;
+$hasFrontendAccess = \Contao\System::getContainer()->get('security.helper')->isGranted('ROLE_MEMBER');
+$hasBackendAccess = \Contao\System::getContainer()->get('contao.security.token_checker')->isPreviewMode();
```

<br>

## ModeConstantToScopeMatcherRector

Fixes deprecated TL_MODE constant to service call

- class: [`Contao\Rector\Rector\ModeConstantToScopeMatcherRector`](../src/Rector/ModeConstantToScopeMatcherRector.php)

```diff
-$isBackend = TL_MODE === 'BE';
+$isBackend = \Contao\System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(\Contao\System::getContainer()->get('request_stack')->getCurrentRequest() ?? \Symfony\Component\HttpFoundation\Request::create(''));
```

<br>

## ReplaceNestedArrayItemRector

Replaces array item values based on a configuration with wild card support and strict types

:wrench: **configure it!**

- class: [`Contao\Rector\Rector\ReplaceNestedArrayItemRector`](../src/Rector/ReplaceNestedArrayItemRector.php)

```diff
-$GLOBALS['TL_DCA']['tl_foo']['config']['dataContainer'] = 'Table';
-$GLOBALS['TL_DCA']['tl_foo']['foo']['bar']['baz'] = 'TYPOlight';
+$GLOBALS['TL_DCA']['tl_foo']['config']['dataContainer'] = \Contao\DC_Table::class;
+$GLOBALS['TL_DCA']['tl_foo']['foo']['bar']['baz'] = 'Contao';
 $GLOBALS['TL_DCA']['tl_complex'] = [
     'config' => [],
     'fields' => [
         'screenshot' => [
             'exclude' => true,
             'inputType' => 'fileTree',
-            'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=> Config::get('validImageTypes')],
+            'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=> '%contao.image.valid_extensions%'],
             'sql' => "binary(16) NULL"
         ],
     ]
 ];
```

<br>

## StringReplaceRector

Replaces text occurrences within a string

:wrench: **configure it!**

- class: [`Contao\Rector\Rector\StringReplaceRector`](../src/Rector/StringReplaceRector.php)

```diff
-$foo = '{foo_legend},foo;{bar_legend:hide},bar;{baz_legend:hide},baz';
+$foo = '{foo_legend},foo;{bar_legend:collapsed},bar;{baz_legend:collapsed},baz';
```

<br>

## SystemLanguagesToServiceRector

Fixes deprecated `\Contao\System::getLanguages()` method to service call

- class: [`Contao\Rector\Rector\SystemLanguagesToServiceRector`](../src/Rector/SystemLanguagesToServiceRector.php)

```diff
-$languaegs = \Contao\System::getLanguages();
+$languages = \Contao\System::getContainer()->get('contao.intl.locales')->getLocales(null, true);
```

<br>

## SystemLogToMonologRector

Rewrites deprecated `System::log()` calls to Monolog

- class: [`Contao\Rector\Rector\SystemLogToMonologRector`](../src/Rector/SystemLogToMonologRector.php)

```diff
-\Contao\System::log('generic log message', __METHOD__, TL_ACCESS);
-\Contao\System::log('error message', __METHOD__, TL_ERROR);
+\Contao\System::getContainer()->get('logger')->log(\Psr\Log\LogLevel::INFO, 'generic log message', ['contao' => new \Contao\CoreBundle\Monolog\ContaoContext(__METHOD__, \Contao\CoreBundle\Monolog\ContaoContext::ACCESS)]);
+\Contao\System::getContainer()->get('logger')->log(\Psr\Log\LogLevel::ERROR, 'error message', ['contao' => new \Contao\CoreBundle\Monolog\ContaoContext(__METHOD__, \Contao\CoreBundle\Monolog\ContaoContext::ERROR)]);
```

<br>
