<?php

declare(strict_types=1);

namespace Emonkak\Di\Benchmarks;

use Brick\Di\InjectionPolicy;

class BrickDiPolicy implements InjectionPolicy
{
    /**
     * {@inheritdoc}
     */
    public function isClassInjected(\ReflectionClass $class): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isMethodInjected(\ReflectionMethod $method): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropertyInjected(\ReflectionProperty $property): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterKey(\ReflectionParameter $parameter): string
    {
        return $parameter->getClass()->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyKey(\ReflectionProperty $property): string
    {
        return $property->getClass()->name;
    }
}
