<?php

namespace Emonkak\Di\Benchmarks\Fixtures;

class Quux
{
    private $waldo;

    public function __construct(Waldo $waldo)
    {
        $this->waldo = $waldo;
    }
}
