<?php

namespace Emonkak\Di\Benchmarks\Fixtures;

class Qux
{
    private $garply;

    public function __construct(Garply $garply)
    {
        $this->garply = $garply;
    }
}
