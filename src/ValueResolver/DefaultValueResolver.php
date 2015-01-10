<?php

namespace Emonkak\Di\ValueResolver;

use Emonkak\Di\Value\ImmediateValue;

class DefaultValueResolver implements ValueResolverInterface
{
    /**
     * {@inheritDoc}
     */
    public function getParameterValue(\ReflectionParameter $param)
    {
        if ($param->isDefaultValueAvailable()) {
            return new ImmediateValue($param->getDefaultValue());
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyValue(\ReflectionProperty $property)
    {
        $name = $property->getName();
        $class = $property->getDeclaringClass();
        $values = $class->getDefaultProperties();

        if (isset($values[$name])) {
            return new ImmediateValue($values[$name]);
        }

        return null;
    }
}
