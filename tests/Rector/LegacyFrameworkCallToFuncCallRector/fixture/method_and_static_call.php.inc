<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        $exists = $this->classFileExists('Foo');
        $exists = Controller::classFileExists('Foo');
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
        $exists = class_exists('Foo');
        $exists = class_exists('Foo');
    }
}
?>
