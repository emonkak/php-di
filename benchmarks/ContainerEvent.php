<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ChainCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;

class ContainerEvent extends AthleticEvent
{
    public function setUp()
    {
        $this->cache = new ApcCache();
    }

    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = Container::create();
        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo')->inject();
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithoutInject()
    {
        $container = Container::create();
        $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo');
    }

    /**
     * @iterations 1000
     */
    public function getWithCache()
    {
        $container = new Container(
            new DefaultInjectionPolicy(),
            new ChainCache([new ArrayCache(), $this->cache])
        );
        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo')->inject();
        assert($foo instanceof Foo);
    }
}
