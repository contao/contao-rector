<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_foo']['fields']['languages'] = [
            'label' => &$GLOBALS['TL_LANG']['MSC']['geoip_countries'],
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => static fn () => \Contao\System::getLanguages(),
            'eval' => ['includeBlankOption' => true, 'mandatory' => true, 'multiple' => true, 'chosen' => true, 'csv' => ',', 'tl_class' => 'w50',],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ];
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_foo']['fields']['languages'] = [
            'label' => &$GLOBALS['TL_LANG']['MSC']['geoip_countries'],
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => static fn () => \Contao\System::getContainer()->get('contao.intl.locales')->getLocales(null, true),
            'eval' => ['includeBlankOption' => true, 'mandatory' => true, 'multiple' => true, 'chosen' => true, 'csv' => ',', 'tl_class' => 'w50',],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ];
    }
}
?>
