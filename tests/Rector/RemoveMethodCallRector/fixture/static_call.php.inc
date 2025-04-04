<?php

class Foo
{
    public function bar()
    {
        $string = new \Contao\StringUtil();
        $foo = $string->toHtml5('bar');
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $string = new \Contao\StringUtil();
        $foo = 'bar';
    }
}
?>
