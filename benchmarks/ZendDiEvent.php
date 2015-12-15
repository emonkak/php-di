<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Auryn\Injector;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Zend\Di\Di;

class ZendDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $di = new Di();

        $di->instanceManager()
            ->addTypePreference('Emonkak\Di\Benchmarks\Fixtures\FooInterface', 'Emonkak\Di\Benchmarks\Fixtures\Foo')
            ->addTypePreference('Emonkak\Di\Benchmarks\Fixtures\BarInterface', 'Emonkak\Di\Benchmarks\Fixtures\Bar')
            ->addTypePreference('Emonkak\Di\Benchmarks\Fixtures\BazInterface', 'Emonkak\Di\Benchmarks\Fixtures\Baz');

        $foo = $di->get('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
