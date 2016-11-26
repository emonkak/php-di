<?php

namespace Emonkak\Di\Tests\Stubs;

use Emonkak\Di\Annotation\Inject;
use Emonkak\Di\Annotation\Qualifier;

class Qux
{
    /**
     * @Inject
     * @Qualifier("$huga")
     */
    public $huga;
}

