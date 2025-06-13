<?php

class Foo extends Controller
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_content']['palettes']['foo'] = <<<PALETTE
            {foo_legend},foo;{bar_legend:hide},bar;{baz_legend:hide},baz;
            PALETTE;
    }
}
?>
-----
<?php

class Foo extends Controller
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_content']['palettes']['foo'] = <<<PALETTE
            {foo_legend},foo;{bar_legend:collapsed},bar;{baz_legend:collapsed},baz;
            PALETTE;
    }
}
?>
