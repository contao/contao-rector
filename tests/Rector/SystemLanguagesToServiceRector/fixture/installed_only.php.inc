<?php

class Foo
{
    public function bar()
    {
        $languages = \Contao\System::getLanguages(true);
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $languages = \Contao\System::getContainer()->get('contao.intl.locales')->getEnabledLocales(null, true);
    }
}
?>
