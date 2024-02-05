<?php

class Foo
{
    public function bar()
    {
        $hasFrontendAccess = true === FE_USER_LOGGED_IN;
        $hasBackendAccess = false === BE_USER_LOGGED_IN;
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $hasFrontendAccess = \Contao\System::getContainer()->get('security.helper')->isGranted('ROLE_MEMBER');
        $hasBackendAccess = !\Contao\System::getContainer()->get('contao.security.token_checker')->isPreviewMode();
    }
}
?>
