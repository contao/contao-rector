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
