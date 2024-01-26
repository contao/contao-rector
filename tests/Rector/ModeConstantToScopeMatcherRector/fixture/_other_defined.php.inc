<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        if (defined('FOOBAR') && TL_MODE == 'FE') {
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
        if (defined('FOOBAR') && \Contao\System::getContainer()->get('contao.routing.scope_matcher')->isFrontendRequest(\Contao\System::getContainer()->get('request_stack')->getCurrentRequest() ?? \Symfony\Component\HttpFoundation\Request::create(''))) {
            // do something
        }
    }
}
?>
