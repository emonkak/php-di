<?php

namespace Emonkak\Di\Benchmarks;

use Brick\Di\InjectionPolicy;

class BrickDiPolicy implements InjectionPolicy
{
    /**
     * {@inheritdoc}
     */
    public function isClassInjected(\ReflectionClass $class)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isMethodInjected(\ReflectionMethod $method)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropertyInjected(\ReflectionProperty $property)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterKey(\ReflectionParameter $parameter)
    {
        return $parameter->getClass()->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyKey(\ReflectionProperty $property)
    {
        return $property->getClass()->name;
    }
}
