<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcacheCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Injector;

class EmonkakDiEvent extends AthleticEvent
{
    private $apcCache;
    private $arrayCache;

    public function setUp()
    {
        if ($this->apcCache === null) {
            $this->apcCache = new ApcCache();
        }

        if ($this->arrayCache === null) {
            $this->arrayCache = new ArrayCache([]);
        }
    }

    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = Container::create();
        $foo = $container->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
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
    public function getWithApcCache()
    {
        $container = new Container(
            new DefaultInjectionPolicy(),
            new ChainCache([new ArrayCache(), $this->apcCache]),
            new \ArrayObject()
        );
        $foo = $container->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithArrayCache()
    {
        $container = new Container(
            new DefaultInjectionPolicy(),
            $this->arrayCache,
            new \ArrayObject()
        );
        $foo = $container->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
