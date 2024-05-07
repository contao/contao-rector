<?php

class Foo
{
    public function bar()
    {
        $sizes = \Contao\System::getImageSizes();
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $sizes = \Contao\System::getContainer()->get('contao.image.sizes')->getAllOptions();
    }
}
?>
