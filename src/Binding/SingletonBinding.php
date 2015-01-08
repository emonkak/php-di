<?php

namespace Emonkak\Di\Binding;

use Emonkak\Di\Container;
use Emonkak\Di\Value\SingletonValue;

class SingletonBinding implements BindingInterface
{
    private $source;

    /**
     * @param BindingInterface $source
     */
    public function __construct(BindingInterface $source)
    {
        $this->source = $source;
    }

    /**
     * {@inheritDoc}
     */
    public function toInjectableValue(Container $container)
    {
        $value = $this->source->toInjectableValue($container);
        return new SingletonValue($value);
    }
}
