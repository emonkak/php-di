<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

class ImmediateValue implements InjectableValueInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(InjectableValueVisitorInterface $visitor)
    {
        return $visitor->visitValue($this);
    }

    /**
     * {@inheritDoc}
     */
    public function inject()
    {
        return $this->value;
    }
}
