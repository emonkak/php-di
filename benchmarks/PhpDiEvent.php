<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Doctrine\Common\Cache\ApcCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use DI\ContainerBuilder;

class PhpDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $builder = new ContainerBuilder();
        $container = $builder->build();
        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithCache()
    {
        $builder = new ContainerBuilder();
        $builder->setDefinitionCache(new ApcCache());
        $container = $builder->build();
        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
