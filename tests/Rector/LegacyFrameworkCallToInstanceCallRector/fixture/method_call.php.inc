<?php

use Contao\Controller;

class Foo extends Controller
{
    public function bar()
    {
        $ids = $this->getChildRecords([42], 'tl_page');
        $ids = $this->getParentRecords(42, 'tl_page');
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
        $ids = \Contao\Database::getInstance()->getChildRecords([42], 'tl_page');
        $ids = \Contao\Database::getInstance()->getParentRecords(42, 'tl_page');
    }
}

?>
