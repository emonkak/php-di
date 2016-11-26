<?php

return [
    'Emonkak\Di\Benchmarks\Fixtures\FooInterface' => DI\object('Emonkak\Di\Benchmarks\Fixtures\Foo')->constructor(
        DI\link('Emonkak\Di\Benchmarks\Fixtures\BarInterface'),
        DI\link('Emonkak\Di\Benchmarks\Fixtures\BazInterface')
    ),
    'Emonkak\Di\Benchmarks\Fixtures\BarInterface' => DI\object('Emonkak\Di\Benchmarks\Fixtures\Bar'),
    'Emonkak\Di\Benchmarks\Fixtures\BazInterface' => DI\object('Emonkak\Di\Benchmarks\Fixtures\Baz'),
];
