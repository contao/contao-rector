<?php

class Foo
{
    public function bar()
    {
        $foo = \Contao\StringUtil::toHtml5('bar');
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $foo = 'bar';
    }
}
?>
