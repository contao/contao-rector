<?php

class Registry
{
    public function import(string $class): void
    {
    }
}

function foo(Registry $registry): void
{
    $registry->import('BackendUser');
}
?>
