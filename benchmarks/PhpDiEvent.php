<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use DI;
use DI\ContainerBuilder;
use Doctrine\Common\Cache\ApcCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;

class PhpDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $builder = new ContainerBuilder();
        $container = $builder->build();
        $container->set(
            'Emonkak\Di\Benchmarks\Fixtures\Foo',
            DI\object()->constructor(
                DI\link('Emonkak\Di\Benchmarks\Fixtures\Bar'),
                DI\link('Emonkak\Di\Benchmarks\Fixtures\Baz')
            )
        );
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
        $container->set(
            'Emonkak\Di\Benchmarks\Fixtures\Foo',
            DI\object()->constructor(
                DI\link('Emonkak\Di\Benchmarks\Fixtures\Bar'),
                DI\link('Emonkak\Di\Benchmarks\Fixtures\Baz')
            )
        );
        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
