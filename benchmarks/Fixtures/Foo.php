<?php

namespace Emonkak\Di\Benchmarks\Fixtures;

class Foo
{
    private $bar;
    private $baz;

    public function __construct(Bar $bar, Baz $baz)
    {
        $this->bar = $bar;
        $this->baz = $baz;
    }
}
