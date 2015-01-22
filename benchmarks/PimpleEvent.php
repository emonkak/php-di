<?php

namespace Emonkak\Di\Benchmarks;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Athletic\AthleticEvent;
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

class PimpleEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = new Container();
        $container['Emonkak\Di\Benchmarks\Fixtures\Foo'] = function($c) {
            return new Foo($c['Emonkak\Di\Benchmarks\Fixtures\Bar'], $c['Emonkak\Di\Benchmarks\Fixtures\Baz']);
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Bar'] = function($c) {
            return new Bar($c['Emonkak\Di\Benchmarks\Fixtures\Qux'], $c['Emonkak\Di\Benchmarks\Fixtures\Quux']);
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Baz'] = function($c) {
            return new Baz($c['Emonkak\Di\Benchmarks\Fixtures\Corge'], $c['Emonkak\Di\Benchmarks\Fixtures\Grault']);
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Qux'] = function($c) {
            return new Qux($c['Emonkak\Di\Benchmarks\Fixtures\Garply']);
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Quux'] = function($c) {
            return new Quux($c['Emonkak\Di\Benchmarks\Fixtures\Waldo']);
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Corge'] = function($c) {
            return new Corge($c['Emonkak\Di\Benchmarks\Fixtures\Fred']);
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Grault'] = function($c) {
            return new Grault($c['Emonkak\Di\Benchmarks\Fixtures\Plugh']);
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Garply'] = function() {
            return new Garply();
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Waldo'] = function() {
            return new Waldo();
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Fred'] = function() {
            return new Fred();
        };
        $container['Emonkak\Di\Benchmarks\Fixtures\Plugh'] = function() {
            return new Plugh();
        };

        $foo = $container['Emonkak\Di\Benchmarks\Fixtures\Foo'];
        assert($foo instanceof Foo);
    }
}
