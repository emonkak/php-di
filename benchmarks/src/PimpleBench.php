<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Corge;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Emonkak\Di\Benchmarks\Fixtures\Fred;
use Emonkak\Di\Benchmarks\Fixtures\Garply;
use Emonkak\Di\Benchmarks\Fixtures\Grault;
use Emonkak\Di\Benchmarks\Fixtures\Plugh;
use Emonkak\Di\Benchmarks\Fixtures\Quux;
use Emonkak\Di\Benchmarks\Fixtures\Qux;
use Emonkak\Di\Benchmarks\Fixtures\Waldo;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @Groups({"di"})
 */
class PimpleBench
{
    public function benchGet()
    {
        $container = new Container();
        $container[FooInterface::class] = function($c) {
            return new Foo($c[Bar::class], $c[Baz::class]);
        };
        $container[Bar::class] = function($c) {
            return new Bar($c[Qux::class], $c[Quux::class]);
        };
        $container[Baz::class] = function($c) {
            return new Baz($c[Corge::class], $c[Grault::class]);
        };
        $container[Qux::class] = function($c) {
            return new Qux($c[Garply::class]);
        };
        $container[Quux::class] = function($c) {
            return new Quux($c[Waldo::class]);
        };
        $container[Corge::class] = function($c) {
            return new Corge($c[Fred::class]);
        };
        $container[Grault::class] = function($c) {
            return new Grault($c[Plugh::class]);
        };
        $container[Garply::class] = function() {
            return new Garply();
        };
        $container[Waldo::class] = function() {
            return new Waldo();
        };
        $container[Fred::class] = function() {
            return new Fred();
        };
        $container[Plugh::class] = function() {
            return new Plugh();
        };

        assert($container[FooInterface::class] instanceof Foo);
    }

    public function benchFactory()
    {
        $container = new Container();
        $container[FooInterface::class] = $container->factory(function($c) {
            return new Foo($c[BarInterface::class], $c[BazInterface::class]);
        });
        $container[BarInterface::class] = $container->factory(function($c) {
            return new Bar($c[Qux::class], $c[Quux::class]);
        });
        $container[BazInterface::class] = $container->factory(function($c) {
            return new Baz($c[Corge::class], $c[Grault::class]);
        });
        $container[Qux::class] = $container->factory(function($c) {
            return new Qux($c[Garply::class]);
        });
        $container[Quux::class] = $container->factory(function($c) {
            return new Quux($c[Waldo::class]);
        });
        $container[Corge::class] = $container->factory(function($c) {
            return new Corge($c[Fred::class]);
        });
        $container[Grault::class] = $container->factory(function($c) {
            return new Grault($c[Plugh::class]);
        });
        $container[Garply::class] = $container->factory(function() {
            return new Garply();
        });
        $container[Waldo::class] = $container->factory(function() {
            return new Waldo();
        });
        $container[Fred::class] = $container->factory(function() {
            return new Fred();
        });
        $container[Plugh::class] = $container->factory(function() {
            return new Plugh();
        });

        assert($container[FooInterface::class] instanceof Foo);
    }
}
