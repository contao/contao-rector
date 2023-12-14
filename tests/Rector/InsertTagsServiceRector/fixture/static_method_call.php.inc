<?php

use Contao\Controller;
use Contao\Module;

class Foo
{
    public function bar()
    {
        $buffer = Controller::replaceInsertTags('foobar');
        $buffer = Controller::replaceInsertTags('foobar', true);
        $buffer = Controller::replaceInsertTags('foobar', false);
        $buffer = Controller::replaceInsertTags('foobar', 'foobar');
        $buffer = Controller::replaceInsertTags('foobar', 1);
        $buffer = Controller::replaceInsertTags('foobar', 0);
        $buffer = Module::replaceInsertTags('foobar', 0);
    }
}
?>
-----
<?php

use Contao\Controller;
use Contao\Module;

class Foo
{
    public function bar()
    {
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replace('foobar');
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replace('foobar');
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replaceInline('foobar');
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replace('foobar');
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replace('foobar');
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replaceInline('foobar');
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replaceInline('foobar');
    }
}
?>
