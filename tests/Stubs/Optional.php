<?php

namespace Emonkak\Di\Tests\Stubs;

class Optional
{
    private $foo;

    private $optionalFoo = null;

    public function __construct(Foo $foo, FooInterface $fooInterface = null)
    {
    }
}
