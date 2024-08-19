<?php

class Foo extends Controller
{
    public function bar()
    {
        $session = \Contao\System::getContainer()->get('session');
    }
}
?>
-----
<?php

class Foo extends Controller
{
    public function bar()
    {
        $session = \Contao\System::getContainer()->get('request_stack')->getSession();
    }
}
?>
