<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        $buffer = '##foo##';
        $arrTokens = ['foo' => 'bar'];
        $buffer = $this->parseSimpleTokens($buffer, $arrTokens);

        $buffer = $this->parseSimpleTokens($buffer, ['foo' => 'bar']);
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
        $buffer = '##foo##';
        $arrTokens = ['foo' => 'bar'];
        $buffer = \Contao\System::getContainer()->get('contao.string.simple_token_parser')->parse($buffer, $arrTokens);

        $buffer = \Contao\System::getContainer()->get('contao.string.simple_token_parser')->parse($buffer, ['foo' => 'bar']);
    }
}
?>
