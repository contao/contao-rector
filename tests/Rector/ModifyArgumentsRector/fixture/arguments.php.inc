<?php

class Foo extends Controller
{
    public function bar()
    {
        \Contao\Input::stripTags(null, '');
        Foo::baz(1, 1);
        Foo::quux(4, 8, 16);
        Foo::corge(true);
        Foo::grault(true, brain: 'goldfish');

        // Don't change anything
        Foo::baz(21, 3, 90);
        Foo::quux(4, 8, 15, 16, 23, 42);
        Foo::corge(true, brain: 'disabledfish');
        Foo::grault(true, brain: 'aschempp');
    }
}
?>
-----
<?php

class Foo extends Controller
{
    public function bar()
    {
        \Contao\Input::stripTags(null, '', \Contao\Config::get('allowedAttributes'));
        Foo::baz(21, 3, 90);
        Foo::quux(4, 8, 15, 16, 23, 42);
        Foo::corge(true, brain: 'disabledfish');
        Foo::grault(true, brain: 'aschempp');

        // Don't change anything
        Foo::baz(21, 3, 90);
        Foo::quux(4, 8, 15, 16, 23, 42);
        Foo::corge(true, brain: 'disabledfish');
        Foo::grault(true, brain: 'aschempp');
    }
}
?>
