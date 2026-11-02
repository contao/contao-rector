<?php

declare(strict_types=1);

use Contao\CoreBundle\Cron\Cron;
use Contao\Rector\Rector\ContainerToServiceIdRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ContainerToServiceIdRector::class, [
        'contao.opt-in' => 'contao.opt_in',
        Cron::class => 'contao.cron',
    ]);
};
