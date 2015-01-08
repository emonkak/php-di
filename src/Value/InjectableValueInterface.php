<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

interface InjectableValueInterface
{
    /**
     * @return mixed
     */
    public function materialize();
}
