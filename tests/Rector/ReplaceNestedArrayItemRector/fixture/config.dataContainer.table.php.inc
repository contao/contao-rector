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
    }
}
?>
