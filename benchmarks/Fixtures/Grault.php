<?php

namespace Emonkak\Di\Benchmarks\Fixtures;

class Grault
{
    private $plugh;

    public function __construct(Plugh $plugh)
    {
        $this->plugh = $plugh;
    }
}
