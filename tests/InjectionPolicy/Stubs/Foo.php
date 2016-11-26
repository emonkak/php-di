<?php

namespace Emonkak\Di\Tests\InjectionPolicy\Stubs;

use Emonkak\Di\Annotation\Inject;
use Emonkak\Di\Annotation\Qualifier;
use Emonkak\Di\Annotation\Scope;

/**
 * @Inject
 * @Scope(Scope::SINGLETON)
 */
class Foo
{
    /**
     * @Inject
     * @Qualifier("$named_foo")
     */
    public $foo;

    /**
     * @Inject
     */
    public $foobar;

    public $bar;

    public $baz;

    public $qux;

    /**
     * @Qualifier(bar="$named_bar")
     */
    public function __construct(Bar $bar, Baz $baz)
    {
        $this->bar = $bar;
        $this->baz = $baz;
    }

    public function setFoo(Foo $foo)
    {
        $this->foo = $foo;
    }

    public function setBar(Bar $bar)
    {
        $this->bar = $bar;
    }

    public function setBaz(Baz $baz)
    {
        $this->baz = $baz;
    }

    /**
     * @Inject
     */
    public function setQux($qux)
    {
        $this->qux = $qux;
    }
}
