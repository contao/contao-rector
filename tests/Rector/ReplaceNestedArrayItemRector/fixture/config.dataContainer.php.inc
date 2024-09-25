<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_foo'] = [
            'config' => [
                'dataContainer' => 'Table'
            ]
        ];

        $GLOBALS['TL_DCA']['tl_bar']['config'] = [
            'dataContainer' => 'File'
        ];

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
        $GLOBALS['TL_DCA']['tl_foo'] = [
            'config' => [
                'dataContainer' => \Contao\DC_Table::class
            ]
        ];

        $GLOBALS['TL_DCA']['tl_bar']['config'] = [
            'dataContainer' => \Contao\DC_File::class
        ];

        $GLOBALS['TL_DCA']['tl_baz']['config']['dataContainer'] = \Contao\DC_Folder::class;
    }
}
?>
