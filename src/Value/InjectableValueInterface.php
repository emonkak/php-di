<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

interface InjectableValueInterface
{
    public function materialize(Injector $injector);
}
