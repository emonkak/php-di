<?php

declare(strict_types=1);

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Emonkak\Di\Benchmarks\Fixtures\Qux;
use Emonkak\Di\Benchmarks\Fixtures\Quux;

return [
    FooInterface::class => DI\autowire(Foo::class),
    BarInterface::class => DI\autowire(Bar::class),
    BazInterface::class => DI\autowire(Baz::class),
];
