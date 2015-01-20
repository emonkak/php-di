<?php

namespace Emonkak\Di\InjectionPolicy;

use Emonkak\Di\Scope\SingletonScope;

class DefaultInjectionPolicy implements InjectionPolicyInterface
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
        return $class ? $class->name : '$' . $param->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyKey(\ReflectionProperty $prop)
    {
        return '$' . $prop->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getScope(\ReflectionClass $class)
    {
        return SingletonScope::getInstance();
    }

    /**
     * {@inheritDoc}
     */
    public function isInjectableClass(\ReflectionClass $class)
    {
        return $class->isInstantiable();
    }
}
