<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Dice\Dice;
use Dice\Instance;
use Dice\Rule;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\Baz;

class DiceEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $rule = new Rule();
        $rule->substitutions['Emonkak\Di\Benchmarks\Fixtures\BarInterface'] = new Instance('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $rule->substitutions['Emonkak\Di\Benchmarks\Fixtures\BazInterface'] = new Instance('Emonkak\Di\Benchmarks\Fixtures\Baz');

        $container = new Dice();
        $container->addRule('*', $rule);

        $foo = $container->create('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
