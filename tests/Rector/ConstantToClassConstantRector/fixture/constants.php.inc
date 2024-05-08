<?php

class Foo extends Controller
{
    public function bar()
    {
        $logLevel = TL_ACCESS;
    }
}
?>
-----
<?php

class Foo extends Controller
{
    public function bar()
    {
        $logLevel = \Contao\CoreBundle\Monolog\ContaoContext::ACCESS;
    }
}
?>
