<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcacheCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\Qux;
use Emonkak\Di\Benchmarks\Fixtures\Quux;
use Emonkak\Di\Benchmarks\Fixtures\Corge;
use Emonkak\Di\Benchmarks\Fixtures\Grault;
use Emonkak\Di\Benchmarks\Fixtures\Garply;
use Emonkak\Di\Benchmarks\Fixtures\Waldo;
use Emonkak\Di\Benchmarks\Fixtures\Fred;
use Emonkak\Di\Benchmarks\Fixtures\Plugh;
use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Injector;
use Emonkak\Di\ServiceProvider\ServiceProviderFactory;
use Emonkak\Di\ServiceProvider\ServiceProviderLoaderOnCache;
use Emonkak\Di\ServiceProvider\ServiceProviderLoaderOnFilesystem;
use Pimple;

class EmonkakDiEvent extends AthleticEvent
{
    private $apcCache;
    private $arrayCache;
    private $serviceProvider;

    public function setUp()
    {
        if ($this->apcCache === null) {
            $this->apcCache = new ApcCache();
        }

        if ($this->arrayCache === null) {
            $this->arrayCache = new ArrayCache([]);
        }

        $serviceProviderFactory = new ServiceProviderFactory(
            Container::create(),
            new ServiceProviderLoaderOnCache($this->apcCache)
        );

        $this->serviceProvider = $serviceProviderFactory
            ->createInstance(['Emonkak\Di\Benchmarks\Fixtures\Foo'], 'MyServiceProvider');
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

    /**
     * @iterations 1000
     */
    public function getWithServicePrividerLoader()
    {
        $pimple = new \Pimple\Container();
        $pimple->register($this->serviceProvider);

        $foo = $pimple['Emonkak\Di\Benchmarks\Fixtures\Foo'];

        assert($foo instanceof Foo);
    }
}
