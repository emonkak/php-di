<?php

namespace Emonkak\Di\Tests\Extras\Stubs;

class Bar
{
    public $baz;

    public function __construct(Baz $baz)
    {
        $this->baz = $baz;
    }
}
