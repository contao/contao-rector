<?php

class Foo extends Controller
{
    public function bar()
    {
        $requestToken = REQUEST_TOKEN;

        $assetsUrl = TL_ASSETS_URL;
        $filesUrl = TL_FILES_URL;
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

        $assetsUrl = \Contao\System::getContainer()->get('contao.assets.assets_context')->getStaticUrl();
        $filesUrl = \Contao\System::getContainer()->get('contao.assets.files_context')->getStaticUrl();
    }
}
?>
