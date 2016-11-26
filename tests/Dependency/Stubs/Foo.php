<?php

namespace Emonkak\Di\Tests\Dependency\Stubs;

class Foo
{
    public $bar;
    public $baz;
    public $qux;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }

    public function setBaz(Baz $baz)
    {
        $this->baz = $baz;
    }
}
