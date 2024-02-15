<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        \Contao\System::log('generic log message', __METHOD__, TL_ACCESS);
        \Contao\System::log('error message', __METHOD__, TL_ERROR);
        \Contao\System::log('error message', __METHOD__, 'ERROR');
        \Contao\System::log('error message', 'some_method', TL_ERROR);
    }
}

?>
-----
<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        \Contao\System::getContainer()->get('monolog.logger.contao')->log(\Psr\Log\LogLevel::INFO, 'generic log message', ['contao' => new \Contao\CoreBundle\Monolog\ContaoContext(__METHOD__, \Contao\CoreBundle\Monolog\ContaoContext::ACCESS)]);
        \Contao\System::getContainer()->get('monolog.logger.contao')->log(\Psr\Log\LogLevel::ERROR, 'error message', ['contao' => new \Contao\CoreBundle\Monolog\ContaoContext(__METHOD__, \Contao\CoreBundle\Monolog\ContaoContext::ERROR)]);
        \Contao\System::getContainer()->get('monolog.logger.contao')->log(\Psr\Log\LogLevel::ERROR, 'error message', ['contao' => new \Contao\CoreBundle\Monolog\ContaoContext(__METHOD__, 'ERROR')]);
        \Contao\System::getContainer()->get('monolog.logger.contao')->log(\Psr\Log\LogLevel::ERROR, 'error message', ['contao' => new \Contao\CoreBundle\Monolog\ContaoContext('some_method', \Contao\CoreBundle\Monolog\ContaoContext::ERROR)]);
    }
}

?>
