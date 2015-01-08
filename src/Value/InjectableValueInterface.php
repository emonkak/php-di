<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

interface InjectableValueInterface
{
    /**
     * @param InjectableValueVisitorInterface $visitor
     * @return mixed
     */
    public function accept(InjectableValueVisitorInterface $visitor);

    /**
     * @return mixed
     */
    public function materialize();
}
