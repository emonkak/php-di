<?php

declare(strict_types=1);

namespace Emonkak\Di\Benchmarks;

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

/**
 * @Groups({"di"})
 */
class NativeBench
{
    public function benchGet()
    {
        $foo = new Foo(
            new Bar(
                new Qux(
                    new Garply()
                ),
                new Quux(
                    new Waldo()
                )
            ),
            new Baz(
                new Corge(
                    new Fred()
                ),
                new Grault(
                    new Plugh()
                )
            )
        );
        assert($foo instanceof Foo);
    }
}
