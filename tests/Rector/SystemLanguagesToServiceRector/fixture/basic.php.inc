<?php

class Foo
{
    public function bar()
    {
        $languages = \Contao\System::getLanguages();
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $languages = \Contao\System::getContainer()->get('contao.intl.locales')->getLocales(null, true);
    }
}
?>
