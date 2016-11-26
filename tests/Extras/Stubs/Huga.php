<?php

namespace Emonkak\Di\Tests\Extras\Stubs;

class Huga
{
    public function __construct(Piyo $piyo)
    {
        $this->piyo = $piyo;
    }
}
