<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        $isNotBackend = TL_MODE === 'FE';
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
        $isNotBackend = \Contao\System::getContainer()->get('contao.routing.scope_matcher')->isFrontendRequest(\Contao\System::getContainer()->get('request_stack')->getCurrentRequest() ?? \Symfony\Component\HttpFoundation\Request::create(''));
    }
}
?>
