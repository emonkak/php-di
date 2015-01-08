<?php

namespace Emonkak\Di\InjectionPolicy;

class InjectionPolicy implements InjectionPolicyInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInjectableMethods(\ReflectionClass $class)
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getInjectableProperties(\ReflectionClass $class)
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterKey(\ReflectionParameter $param)
    {
        $class = $param->getClass();
        return $class ? $class->getName() : '$' . $param->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyKey(\ReflectionProperty $prop)
    {
        return '$' . $prop->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function isInjectable(\ReflectionClass $class)
    {
        return $class->isInstantiable();
    }

    /**
     * {@inheritDoc}
     */
    public function isSingleton(\ReflectionClass $class)
    {
        return true;
    }
}
