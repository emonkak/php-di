<?php

namespace Emonkak\Di\Binding;

use Emonkak\Di\Container;
use Emonkak\Di\Value\SingletonValue;

class SingletonBinding implements BindingInterface
{
    private $target;

    /**
     * @param \ReflectionClass $target
     */
    public function __construct(\ReflectionClass $target)
    {
        $this->target = $target;
    }

    /**
     * {@inheritDoc}
     */
    public function toInjectableValue(Container $container)
    {
        $injectionFinder = $container->getInjectionFinder();
        $constructorInjection = $injectionFinder->getConstructorInjection($this->target);
        $methodInjections = $injectionFinder->getMethodInjections($this->target);
        $propertyInjections = $injectionFinder->getPropertyInjections($this->target);
        return new SingletonValue(
            $this->target,
            $constructorInjection,
            $methodInjections,
            $propertyInjections
        );
    }
}
