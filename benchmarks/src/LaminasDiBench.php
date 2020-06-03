<?php

declare(strict_types=1);

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Zend\Di\Di;
use Laminas\Di\Injector;
use Laminas\Di\Config;

/**
 * @Groups({"di"})
 */
class LaminasDiBench
{
    public function benchGet()
    {
        $injector = new Injector(new Config([
            'preferences' => [
                FooInterface::class => Foo::class,
                BarInterface::class => Bar::class,
                BazInterface::class => Baz::class,
            ],
        ]));

        assert($injector->create(Foo::class) instanceof Foo);
    }
}
