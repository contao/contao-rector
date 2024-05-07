<?php

declare(strict_types=1);

use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\Rector\Rector\ConstantToClassConstantRector;
use Contao\Rector\ValueObject\ConstantToClassConstant;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ConstantToClassConstantRector::class, [
        new ConstantToClassConstant('TL_ACCESS', ContaoContext::class, 'ACCESS')
    ]);
};
