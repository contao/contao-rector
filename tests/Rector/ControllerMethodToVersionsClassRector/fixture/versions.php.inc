<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        $this->createInitialVersion('tl_page', 42);
        $this->createNewVersion('tl_foo', 24);
    }
}
?>
-----
<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        (new \Contao\Versions('tl_page', 42))->initialize();
        (new \Contao\Versions('tl_foo', 24))->create();
    }
}
?>
