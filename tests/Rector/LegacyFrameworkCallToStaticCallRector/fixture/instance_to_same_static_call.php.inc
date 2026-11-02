<?php

use Contao\Input;

class Foo
{
    public function bar(Input $input)
    {
        $id = $input->get('id');
        $id = Input::get('id');
    }
}
?>
-----
<?php

use Contao\Input;

class Foo
{
    public function bar(Input $input)
    {
        $id = \Contao\Input::get('id');
        $id = Input::get('id');
    }
}
?>
