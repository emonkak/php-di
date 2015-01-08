<?php

namespace Emonkak\Di\Binding;

use Emonkak\Di\Container;
use Emonkak\Di\Value\ImmediateValue;

class ValueBinding implements BindingInterface
{
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritDoc}
     */
    public function toInjectableValue(Container $container)
    {
        return new ImmediateValue($this->value);
    }
}
