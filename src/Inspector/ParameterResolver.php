<?php

declare(strict_types=1);

namespace Emonkak\Di\Inspector;

class ParameterResolver implements ParameterResolverInterface
{
    public function resolveKey(\ReflectionParameter $parameter): string
    {
        $class = $parameter->getClass();
        return $class !== null ? $class->name : '$' . $parameter->name;
    }

    public function resolveClass(\ReflectionParameter $parameter): ?\ReflectionClass
    {
        return $parameter->getClass();
    }
}
