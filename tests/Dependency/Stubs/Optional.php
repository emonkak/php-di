<?php

namespace Emonkak\Di\Tests\Dependency\Stubs;

class Optional
{
    public $bar;
    public $optionalBar = 123;
    public $optionalBaz = 123;

    public function __construct(Bar $bar, BarInterface $optionalBar = null, BazInterface $optionalBaz = null)
    {
    }
}

