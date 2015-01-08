<?php

namespace Emonkak\Di\ValueResolver;

use Emonkak\Di\Value\InjectableValueInterface;

interface ValueResolverInterface
{
    /**
     * @param \ReflectionParameter $param
     * @return InjectableValueInterface
     */
    public function getParameterValue(\ReflectionParameter $param);

    /**
     * @param \ReflectionProperty $property
     * @return InjectableValueInterface
     */
    public function getPropertyValue(\ReflectionProperty $property);
}
