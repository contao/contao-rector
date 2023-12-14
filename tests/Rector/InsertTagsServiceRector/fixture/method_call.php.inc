<?php

use Contao\Controller;
use Contao\InsertTags;

class Foo extends Controller
{
    public function bar()
    {
        $buffer = static::replaceInsertTags('foobar', false);
        $buffer = self::replaceInsertTags('foobar', 0);
        $buffer = $this->replaceInsertTags('foobar');

        $buffer = (new InsertTags())->replace('foobar', false);
        $insertTags = new InsertTags();
        $buffer = $insertTags->replace('foobar');
    }
}
?>
-----
<?php

use Contao\Controller;
use Contao\InsertTags;

class Foo extends Controller
{
    public function bar()
    {
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replaceInline('foobar');
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replaceInline('foobar');
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replace('foobar');

        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replaceInline('foobar');
        $insertTags = new InsertTags();
        $buffer = \Contao\System::getContainer()->get('contao.insert_tag.parser')->replace('foobar');
    }
}
?>
