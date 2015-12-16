<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use League\Container\Container;
use League\Container\ReflectionContainer;

class LeagueContainerEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = new Container();
        $container->delegate(new ReflectionContainer());

        $container->add('Emonkak\Di\Benchmarks\Fixtures\FooInterface', 'Emonkak\Di\Benchmarks\Fixtures\Foo')
                ->withArgument('Emonkak\Di\Benchmarks\Fixtures\BarInterface')
                ->withArgument('Emonkak\Di\Benchmarks\Fixtures\BazInterface');
        $container->add('Emonkak\Di\Benchmarks\Fixtures\BarInterface', 'Emonkak\Di\Benchmarks\Fixtures\Bar')
                ->withArgument('Emonkak\Di\Benchmarks\Fixtures\Qux')
                ->withArgument('Emonkak\Di\Benchmarks\Fixtures\Quux');
        $container->add('Emonkak\Di\Benchmarks\Fixtures\BazInterface', 'Emonkak\Di\Benchmarks\Fixtures\Baz')
                ->withArgument('Emonkak\Di\Benchmarks\Fixtures\Corge')
                ->withArgument('Emonkak\Di\Benchmarks\Fixtures\Grault');

        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\FooInterface');
        assert($foo instanceof Foo);
    }
}
