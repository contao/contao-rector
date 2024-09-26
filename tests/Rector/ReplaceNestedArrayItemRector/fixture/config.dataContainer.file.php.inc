<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_bar']['config'] = [
            'dataContainer' => 'File'
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
        $GLOBALS['TL_DCA']['tl_bar']['config'] = [
            'dataContainer' => \Contao\DC_File::class
        ];
    }
}
?>
