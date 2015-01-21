<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Dice\Dice;
use Emonkak\Di\Benchmarks\Fixtures\Foo;

class DiceEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = new Dice();
        $foo = $container->create('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
