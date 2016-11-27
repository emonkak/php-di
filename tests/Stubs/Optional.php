<?php

namespace Emonkak\Di\Tests\Stubs;

class Optional
{
    public $foo;

    public $optionalFoo = 123;

    public function __construct(Foo $foo, FooInterface $fooInterface = null)
    {
    }
}
