# Rector Rules for Contao Open Source CMS

This project contains [Rector rules](https://github.com/rectorphp/rector)
for [Contao Open Source CMS](https://contao/contao) upgrades.

**!! WARNING !! this is currently experimental, use at your own risk**

## Install

Install contao-rector via composer to your project:

```bash
composer require contao/contao-rector --dev
```

## Available sets

| Sets                                           | Description                                                                                      |
|:-----------------------------------------------|:-------------------------------------------------------------------------------------------------|
| ```ContaoSetList::CONTAO_49```                 | updates your code to compatibility with Contao 4.9                                               |
| ```ContaoSetList::CONTAO_413```                | updates your code to compatibility with Contao 4.13                                              |
| ```ContaoSetList::CONTAO_50```                 | updates your code to compatibility with Contao 5.0                                               |
| ```ContaoSetList::ANNOTATIONS_TO_ATTRIBUTES``` | converts Contao annotations (e.g. `@Hook("...")`) to attributes (e.g. `#[AsHook('...')]`)        |
| ```ContaoSetList::FQCN```                      | upgrades class namespaces from global (e.g. `\StringUtil`) to Contao (e.g. `\Contao\StringUtil`) |

## Available level sets

Level sets combine multiple changes for a specific Contao version, including
dependencies like PHP, Symfony and Doctrine.

As an example, the **ContaoLevelSetList::UP_TO_CONTAO_413** will upgrade your code
to PHP 7.4 and Symfony 5.4, since Contao 4.13 does not support any lower versions.

## Available rules

* [Explore the current Rector rules](/docs/rules_overview.md)

### Development

You can generate the rules with the following command:

```shell
vendor/bin/rule-doc-generator generate src/Rector --output-file docs/rules_overview.md
```
