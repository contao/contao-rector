<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_baz']['config']['dataContainer'] = 'Folder';
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_baz']['config']['dataContainer'] = \Contao\DC_Folder::class;
    }
}
?>
