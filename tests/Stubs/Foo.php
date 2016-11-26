<?php

namespace Emonkak\Di\Tests\Stubs;

use Emonkak\Di\Annotation\Inject;
use Emonkak\Di\Annotation\Qualifier;

class Foo implements FooInterface
{
    public $bar;
    public $baz;

    /**
     * @Inject
     * @Qualifier("$hoge")
     */
    public $hoge;

    public function __construct(BarInterface $bar, BazInterface $baz)
    {
        $this->bar = $bar;
        $this->baz = $baz;
    }
}
