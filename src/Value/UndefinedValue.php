<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

class UndefinedValue implements InjectableValueInterface
{
    /**
     * {@inheritDoc}
     */
    public function accept(InjectableValueVisitorInterface $visitor)
    {
        return $visitor->visitValue($this);
    }

    /**
     * @return mixed
     */
    public function materialize()
    {
        return new \RuntimeException('This value can not be materialized.');
    }
}
