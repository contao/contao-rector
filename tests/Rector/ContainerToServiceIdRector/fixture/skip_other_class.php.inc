<?php

class Registry
{
    public function get(string $id): void
    {
    }
}

function foo(Registry $registry): void
{
    $registry->get('contao.opt-in');
}
?>
