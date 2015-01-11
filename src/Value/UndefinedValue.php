<?php

namespace Emonkak\Di\Value;

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
    public function inject()
    {
        return new \RuntimeException('This value can not be injected.');
    }
}
