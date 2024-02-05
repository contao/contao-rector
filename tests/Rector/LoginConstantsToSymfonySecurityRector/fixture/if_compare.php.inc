<?php

class Foo
{
    public function bar()
    {
        if (FE_USER_LOGGED_IN === true) {
            // do something
        }

        if (FE_USER_LOGGED_IN === false) {
            // do something
        }

        if (true === BE_USER_LOGGED_IN) {
            // do something
        }

        if (false === BE_USER_LOGGED_IN) {
            // do something
        }

        if (true === BE_USER_LOGGED_IN && $foo === 'bar') {
            // do something
        }
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        if (\Contao\System::getContainer()->get('security.helper')->isGranted('ROLE_MEMBER')) {
            // do something
        }

        if (!\Contao\System::getContainer()->get('security.helper')->isGranted('ROLE_MEMBER')) {
            // do something
        }

        if (\Contao\System::getContainer()->get('contao.security.token_checker')->isPreviewMode()) {
            // do something
        }

        if (!\Contao\System::getContainer()->get('contao.security.token_checker')->isPreviewMode()) {
            // do something
        }

        if (\Contao\System::getContainer()->get('contao.security.token_checker')->isPreviewMode() && $foo === 'bar') {
            // do something
        }
    }
}
?>
