<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        $theme = $this->getTheme();
        $foo = $this->restoreBasicEntities('foo');
    }
}
?>
-----
<?php

use Contao\Controller;

class Foo
{
    public function bar()
    {
        $theme = \Contao\Backend::getTheme();
        $foo = \Contao\StringUtil::restoreBasicEntities('foo');
    }
}
?>
