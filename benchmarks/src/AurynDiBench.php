<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticBench;
use Auryn\Injector;
use Emonkak\Di\Benchmarks\Fixtures\Foo;

/**
 * @Groups({"di"})
 */
class AurynDiBench
{
    public function benchGet()
    {
        $injector = new Injector();

        $injector->alias('Emonkak\Di\Benchmarks\Fixtures\FooInterface', 'Emonkak\Di\Benchmarks\Fixtures\Foo');
        $injector->alias('Emonkak\Di\Benchmarks\Fixtures\BarInterface', 'Emonkak\Di\Benchmarks\Fixtures\Bar');
        $injector->alias('Emonkak\Di\Benchmarks\Fixtures\BazInterface', 'Emonkak\Di\Benchmarks\Fixtures\Baz');

        assert($injector->make('Emonkak\Di\Benchmarks\Fixtures\FooInterface') instanceof Foo);
    }
}
