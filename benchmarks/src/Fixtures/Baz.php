<?php

namespace Emonkak\Di\Benchmarks\Fixtures;

class Baz implements BazInterface
{
    private $corge;
    private $grault;

    public function __construct(Corge $corge, Grault $grault)
    {
        $this->corge = $corge;
        $this->grault = $grault;
    }
}
