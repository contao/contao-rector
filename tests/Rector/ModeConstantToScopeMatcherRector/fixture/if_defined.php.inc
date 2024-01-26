<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        if (defined('TL_MODE') && TL_MODE == 'FE') {
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
        if (\Contao\System::getContainer()->get('contao.routing.scope_matcher')->isFrontendRequest(\Contao\System::getContainer()->get('request_stack')->getCurrentRequest() ?? \Symfony\Component\HttpFoundation\Request::create(''))) {
            // do something
        }
    }
}
?>
