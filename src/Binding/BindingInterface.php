<?php

namespace Emonkak\Di\Binding;

use Emonkak\Di\Container;
use Emonkak\Di\Value\InjectableValueInterface;

interface BindingInterface
{
    /**
     * @param Container $container
     * @return InjectableValueInterface
     */
    public function toInjectableValue(Container $container);
}
