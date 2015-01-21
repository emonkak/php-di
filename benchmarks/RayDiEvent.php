<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Doctrine\Common\Cache\ApcCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Ray\Di\Injector;
use Ray\Di\CacheInjector;

class RayDiEvent extends AthleticEvent
{
    public function setUp()
    {
        $this->cache = new ApcCache();
        $this->initialization = function() {};
        $this->injectorProvider = function() {
            return Injector::create();
        };
    }

    /**
     * @iterations 1000
     */
    public function get()
    {
        $injector = Injector::create();
        $foo = $injector->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithCache()
    {
        $injector = new CacheInjector($this->injectorProvider, $this->initialization, 'cache-namespace', $this->cache);
        $foo = $injector->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
