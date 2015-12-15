<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Aura\Di\Container;
use Aura\Di\Forge;
use Aura\Di\Config;
use Emonkak\Di\Benchmarks\Fixtures\Foo;

class AuraDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = new Container(new Forge(new Config()));

        $container->set('Emonkak\Di\Benchmarks\Fixtures\FooInterface', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Foo'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Foo'] = [
            'bar' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Bar'),
            'baz' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Baz'),
        ];

        $container->set('Emonkak\Di\Benchmarks\Fixtures\BarInterface', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Bar'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Bar'] = [
            'qux' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Qux'),
            'quux' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Quux'),
        ];

        $container->set('Emonkak\Di\Benchmarks\Fixtures\BazInterface', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Baz'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Baz'] = [
            'corge' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Corge'),
            'grault' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Grault'),
        ];

        $container->set('Emonkak\Di\Benchmarks\Fixtures\Qux', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Qux'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Qux'] = [
            'garply' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Garply'),
        ];
        $container->set('Emonkak\Di\Benchmarks\Fixtures\Quux', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Quux'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Quux'] = [
            'waldo' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Waldo'),
        ];
        $container->set('Emonkak\Di\Benchmarks\Fixtures\Corge', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Corge'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Corge'] = [
            'fred' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Fred'),
        ];
        $container->set('Emonkak\Di\Benchmarks\Fixtures\Grault', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Grault'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Grault'] = [
            'plugh' => $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Plugh'),
        ];

        $container->set('Emonkak\Di\Benchmarks\Fixtures\Garply', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Garply'));
        $container->set('Emonkak\Di\Benchmarks\Fixtures\Waldo', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Waldo'));
        $container->set('Emonkak\Di\Benchmarks\Fixtures\Fred', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Fred'));
        $container->set('Emonkak\Di\Benchmarks\Fixtures\Plugh', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Plugh'));

        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\FooInterface');
        assert($foo instanceof Foo);
    }
}
