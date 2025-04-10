<?php

class Foo
{
    use \Contao\CoreBundle\File\ModelMetadataTrait;
}
?>
-----
<?php

class Foo
{
    use \Contao\Model\MetadataTrait;
}
?>
