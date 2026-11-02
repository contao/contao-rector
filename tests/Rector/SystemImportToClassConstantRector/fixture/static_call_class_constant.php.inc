<?php

use Contao\System;

class Foo extends System
{
    public function bar(): void
    {
        $this->import('BackendUser', 'User');
        System::import('BackendUser');
        $this->import('FrontendUser');
    }
}
?>
-----
<?php

use Contao\System;

class Foo extends System
{
    public function bar(): void
    {
        $this->import(\Contao\BackendUser::class, 'User');
        System::import(\Contao\BackendUser::class);
        $this->import('FrontendUser');
    }
}
?>
