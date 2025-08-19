<?php

declare(strict_types=1);

use Contao\Config;
use Contao\Input;
use Contao\Rector\Rector\ModifyArgumentsRector;
use Contao\Rector\ValueObject\ModifyArguments;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ModifyArgumentsRector::class, [
        new ModifyArguments(Input::class, 'stripTags', [
            2 => new StaticCall(new FullyQualified(Config::class), 'get', [new Arg(new String_('allowedAttributes'))])
        ]),
        new ModifyArguments(Foo::class, 'baz', [
            0 => new Int_(21),
            1 => new Int_(3),
            2 => new Int_(90)
        ]),
        new ModifyArguments(Foo::class, 'quux', [
            2 => new Int_(15),
            3 => new Int_(16),
            4 => new Int_(23),
            5 => new Int_(42)
        ]),
        new ModifyArguments(Foo::class, 'corge', [
            'brain' => new String_('disabledfish')
        ]),
        new ModifyArguments(Foo::class, 'grault', [
            'brain' => new String_('aschempp')
        ])
    ]);
};
