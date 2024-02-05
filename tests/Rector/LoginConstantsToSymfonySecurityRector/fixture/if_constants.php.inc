<?php

class Foo
{
    public function bar()
    {
        if (FE_USER_LOGGED_IN) {
            // do something
        }

        if (!BE_USER_LOGGED_IN) {
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

        if (!\Contao\System::getContainer()->get('contao.security.token_checker')->isPreviewMode()) {
            // do something
        }
    }
}
?>
