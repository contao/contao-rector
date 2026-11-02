<?php

use Contao\CoreBundle\Cron\Cron;
use Symfony\Component\DependencyInjection\ContainerInterface;

function foo(ContainerInterface $container): void
{
    $container->get('contao.opt-in');
    $container->get(Cron::class);
    $container->get('contao.framework');
}
?>
-----
<?php

use Contao\CoreBundle\Cron\Cron;
use Symfony\Component\DependencyInjection\ContainerInterface;

function foo(ContainerInterface $container): void
{
    $container->get('contao.opt_in');
    $container->get('contao.cron');
    $container->get('contao.framework');
}
?>
