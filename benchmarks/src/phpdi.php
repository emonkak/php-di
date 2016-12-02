<?php

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;

return [
    FooInterface::class => DI\object(Foo::class)->constructor(
        DI\link(BarInterface::class),
        DI\link(BazInterface::class)
    ),
    BarInterface::class => DI\object(Bar::class),
    BazInterface::class => DI\object(Baz::class),
];
