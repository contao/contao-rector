<?php

class Foo
{
    public function bar(string $value)
    {
        $data = deserialize($value, true);
    }
}
?>
-----
<?php

class Foo
{
    public function bar(string $value)
    {
        $data = \Contao\StringUtil::deserialize($value, true);
    }
}
?>
