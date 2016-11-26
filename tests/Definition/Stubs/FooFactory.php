<?php

namespace Emonkak\Di\Tests\Definition\Stubs;

class FooFactory
{
    public function __invoke(BarInterface $bar, BazInterface $baz)
    {
        $foo = new Foo($bar);
        $foo->setBaz($baz);
        return $foo;
    }
}
