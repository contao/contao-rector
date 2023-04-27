<?php

declare(strict_types=1);

use Contao\CoreBundle\DependencyInjection\Attribute\AsPickerProvider;
use Contao\CoreBundle\DependencyInjection\Attribute\AsPage;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCronJob;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\ServiceAnnotation\PickerProvider;
use Contao\CoreBundle\ServiceAnnotation\Page;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\CoreBundle\ServiceAnnotation\CronJob;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute(Callback::class, AsCallback::class),
        new AnnotationToAttribute(ContentElement::class, AsContentElement::class),
        new AnnotationToAttribute(CronJob::class, AsCronJob::class),
        new AnnotationToAttribute(FrontendModule::class, AsFrontendModule::class),
        new AnnotationToAttribute(Hook::class, AsHook::class),
        new AnnotationToAttribute(Page::class, AsPage::class),
        new AnnotationToAttribute(PickerProvider::class, AsPickerProvider::class),
    ]);
};
