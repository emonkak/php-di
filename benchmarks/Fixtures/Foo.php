<?php

namespace Emonkak\Di\Benchmarks\Fixtures;

class Foo
{
    private $bar;
    private $baz;

    /**
     * @Ray\Di\Di\Inject
     */
    public function __construct(BarInterface $bar, BazInterface $baz)
    {
        $this->bar = $bar;
        $this->baz = $baz;
    }
}
