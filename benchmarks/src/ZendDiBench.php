<?php

namespace Emonkak\Di\Benchmarks;

use Auryn\Injector;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Zend\Di\Di;

/**
 * @Groups({"di"})
 */
class ZendDiBench
{
    public function benchGet()
    {
        $di = new Di();

        $di->instanceManager()
            ->addTypePreference('Emonkak\Di\Benchmarks\Fixtures\FooInterface', 'Emonkak\Di\Benchmarks\Fixtures\Foo')
            ->addTypePreference('Emonkak\Di\Benchmarks\Fixtures\BarInterface', 'Emonkak\Di\Benchmarks\Fixtures\Bar')
            ->addTypePreference('Emonkak\Di\Benchmarks\Fixtures\BazInterface', 'Emonkak\Di\Benchmarks\Fixtures\Baz');

        assert($di->get('Emonkak\Di\Benchmarks\Fixtures\Foo') instanceof Foo);
    }
}
