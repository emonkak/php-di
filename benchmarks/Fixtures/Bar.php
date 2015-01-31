<?php

namespace Emonkak\Di\Benchmarks\Fixtures;

class Bar implements BarInterface
{
    private $qux;
    private $quux;

    public function __construct(Qux $qux, Quux $quux)
    {
        $this->qux = $qux;
        $this->quux = $quux;
    }
}
