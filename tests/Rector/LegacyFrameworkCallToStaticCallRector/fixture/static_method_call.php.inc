<?php

use Contao\Backend;
use Contao\Controller;

class Foo
{
    public function bar()
    {
        $theme = Controller::getTheme();
        $theme = Backend::getTheme();
    }
}
?>
-----
<?php

use Contao\Backend;
use Contao\Controller;

class Foo
{
    public function bar()
    {
        $theme = \Contao\Backend::getTheme();
        $theme = Backend::getTheme();
    }
}
?>
