<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        $isNotBackend = TL_MODE === 'BE';
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
        $isNotBackend = \Contao\System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(\Contao\System::getContainer()->get('request_stack')->getCurrentRequest() ?? \Symfony\Component\HttpFoundation\Request::create(''));
    }
}
?>
