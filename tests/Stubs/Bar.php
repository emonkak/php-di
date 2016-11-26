<?php

namespace Emonkak\Di\Tests\Stubs;

class Bar implements BarInterface
{
    public $baz;

    public function __construct(BazInterface $baz)
    {
        $this->baz = $baz;
    }
}
