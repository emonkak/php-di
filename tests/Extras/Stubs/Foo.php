<?php

namespace Emonkak\Di\Tests\Extras\Stubs;

class Foo
{
    public $bar;
    public $baz;
    public $qux;
    public $quux;
    public $corge;
    public $grault;
    public $any;
    public $optional1;
    public $optional2;

    public function __construct(Bar $bar, Baz $baz, $optional1 = 'optional', $optional2 = 123)
    {
        $this->bar = $bar;
        $this->baz = $baz;
        $this->optional1 = $optional1;
        $this->optional2 = $optional2;
    }

    public function setQux(Qux $qux)
    {
        $this->qux = $qux;
    }

    public function setQuux(Quux $quux)
    {
        $this->quux = $quux;
    }

    public function setAny($any)
    {
        $this->any = $any;
    }
}
