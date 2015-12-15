<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Auryn\Injector;
use Emonkak\Di\Benchmarks\Fixtures\Foo;

class AurynDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $injector = new Injector();

        $injector->alias('Emonkak\Di\Benchmarks\Fixtures\FooInterface', 'Emonkak\Di\Benchmarks\Fixtures\Foo');
        $injector->alias('Emonkak\Di\Benchmarks\Fixtures\BarInterface', 'Emonkak\Di\Benchmarks\Fixtures\Bar');
        $injector->alias('Emonkak\Di\Benchmarks\Fixtures\BazInterface', 'Emonkak\Di\Benchmarks\Fixtures\Baz');

        $foo = $injector->make('Emonkak\Di\Benchmarks\Fixtures\FooInterface');
        assert($foo instanceof Foo);
    }
}
