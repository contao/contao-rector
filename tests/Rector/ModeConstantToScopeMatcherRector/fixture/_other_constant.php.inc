<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        $something = TL_FOO === 'BAR';
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
        $something = TL_FOO === 'BAR';
    }
}
?>
