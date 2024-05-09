<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_foo']['fields']['size'] = [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['size'],
            'exclude' => true,
            'inputType' => 'imageSize',
            'options' => \Contao\System::getImageSizes(),
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
            'sql' => "varchar(64) NOT NULL default ''"
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
        $GLOBALS['TL_DCA']['tl_foo']['fields']['size'] = [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['size'],
            'exclude' => true,
            'inputType' => 'imageSize',
            'options' => \Contao\System::getContainer()->get('contao.image.sizes')->getAllOptions(),
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
            'sql' => "varchar(64) NOT NULL default ''"
        ];
    }
}
?>
