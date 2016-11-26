<?php

namespace Emonkak\Di\Tests\Stubs;

use Emonkak\Di\Annotation\Inject;
use Emonkak\Di\Annotation\Qualifier;

class Baz implements BazInterface
{
    public $piyo;
    public $payo;
    public $poyo;

    /**
     * @Inject
     * @Qualifier("$piyo")
     */
    public function setPiyo($piyo)
    {
        $this->piyo = $piyo;
    }

    /**
     * @Inject
     * @Qualifier("$payo")
     */
    public function setPayo($payo)
    {
        $this->payo = $payo;
    }

    /**
     * @Inject
     * @Qualifier("$poyo")
     */
    public function setPoyo($poyo)
    {
        $this->poyo = $poyo;
    }
}
