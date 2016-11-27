<?php

namespace Emonkak\Di\Tests\Stubs;

class Required
{
    private $foo;

    public function __construct(FooInterface $fooInterface)
    {
    }
}
