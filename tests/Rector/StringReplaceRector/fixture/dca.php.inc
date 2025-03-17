<?php

class Foo extends Controller
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_content']['palettes']['foo'] = '{foo_legend},foo;{bar_legend:hide},bar;{baz_legend:hide},baz';
    }
}
?>
-----
<?php

class Foo extends Controller
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_content']['palettes']['foo'] = '{foo_legend},foo;{bar_legend:collapsed},bar;{baz_legend:collapsed},baz';
    }
}
?>
