<?php

class Foo
{
    public function bar()
    {
        $extensions = \Contao\Config::get('validImageTypes');
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $extensions = '%contao.image.valid_extensions%';
    }
}
?>
