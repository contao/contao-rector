<?php

class Foo extends Controller
{
    public function bar()
    {
        $requestToken = REQUEST_TOKEN;
    }
}
?>
-----
<?php

class Foo extends Controller
{
    public function bar()
    {
        $requestToken = \Contao\System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();
    }
}
?>
