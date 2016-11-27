<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticBench;
use Auryn\Injector;
use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;

/**
 * @Groups({"di"})
 */
class AurynDiBench
{
    public function benchGet()
    {
        $injector = new Injector();

        $injector->alias(FooInterface::class, Foo::class);
        $injector->alias(BarInterface::class, Bar::class);
        $injector->alias(BazInterface::class, Baz::class);

        assert($injector->make(FooInterface::class) instanceof Foo);
    }
}
