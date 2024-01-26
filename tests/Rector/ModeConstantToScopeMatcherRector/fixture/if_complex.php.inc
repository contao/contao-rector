<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        if (TL_MODE == 'BE' && $foo === 'bar') {
            // do something
        }
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
        if (\Contao\System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(\Contao\System::getContainer()->get('request_stack')->getCurrentRequest() ?? \Symfony\Component\HttpFoundation\Request::create('')) && $foo === 'bar') {
            // do something
        }
    }
}
?>
