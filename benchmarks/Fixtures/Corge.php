<?php

namespace Emonkak\Di\Benchmarks\Fixtures;

class Corge
{
    private $fred;

    public function __construct(Fred $fred)
    {
        $this->fred = $fred;
    }
}
