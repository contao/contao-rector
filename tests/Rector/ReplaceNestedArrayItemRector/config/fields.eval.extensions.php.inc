<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_foo']['fields'] = [
            'bar' => [
                'exclude' => true,
                'inputType' => 'fileTree',
                'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=>\Contao\Config::get('validImageTypes')],
                'sql' => "binary(16) NULL"
            ],
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
        /*$GLOBALS['TL_DCA']['tl_foo']['fields'] = [
            'bar' => [
                'exclude' => true,
                'inputType' => 'fileTree',
                'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=>'%contao.image.valid_extensions%'],
                'sql' => "binary(16) NULL"
            ],
        ];*/
    }
}
?>
