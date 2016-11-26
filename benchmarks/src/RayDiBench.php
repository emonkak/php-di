<?php

namespace Emonkak\Di\Benchmarks;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ApcuCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Ray\Di\CacheInjector;
use Ray\Di\Injector;

/**
 * @Groups({"di"})
 */
class RayDiBench
{
    private $cachedInjector;

    public function benchGet()
    {
        $injector = new Injector(new RayDiModule());
        assert($injector->getInstance('Emonkak\Di\Benchmarks\Fixtures\FooInterface') instanceof Foo);
    }

    /**
     * @BeforeMethods({"prepareCachedInjector"})
     */
    public function benchGetWithCache()
    {
        $injector = unserialize($this->cachedInjector);
        assert($injector->getInstance('Emonkak\Di\Benchmarks\Fixtures\FooInterface') instanceof Foo);
    }

    public function prepareCachedInjector()
    {
        $injector = new Injector(new RayDiModule());
        $this->cachedInjector = serialize($injector);
    }
}
