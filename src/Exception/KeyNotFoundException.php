<?php

namespace Emonkak\Di\Exception;

use Interop\Container\Exception\NotFoundException;

class KeyNotFoundException extends \RuntimeException implements NotFoundException
{
    /**
     * @param string              $key
     * @param \ReflectionProperty $property
     * @param NotFoundException   $prev
     * @return NotFoundException
     */
    public static function fromProperty($key, \ReflectionProperty $property, NotFoundException $prev)
    {
        $reflectionClass = $property->getDeclaringClass();

        return new NotFoundException(sprintf(
            'Error while resolving "%s" from "%s::$%s" in %s:%d',
            $key,
            $reflectionClass->name,
            $property->name,
            $reflectionClass->getFileName(),
            $reflectionClass->getStartLine()
        ), 0, $prev);
    }

    /**
     * @param string               $key
     * @param \ReflectionParameter $parameter
     * @param NotFoundException    $prev
     * @return NotFoundException
     */
    public static function fromParameter($key, \ReflectionParameter $parameter, NotFoundException $prev)
    {
        $reflectionFunction = $parameter->getDeclaringFunction();
        $reflectionClass = $parameter->getDeclaringClass();

        $source = $reflectionClass
            ? sprintf('%s::%s()', $reflectionClass->name, $reflectionFunction->name)
            : $reflectionFunction->name;

        return new NotFoundException(sprintf(
            'Error while resolving "%s" from "%s" in %s:%d',
            $key,
            $source,
            $reflectionFunction->getFileName(),
            $reflectionFunction->getStartLine()
        ), 0, $prev);
    }
}
