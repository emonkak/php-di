<?php

namespace Emonkak\Di\Exception;

use Interop\Container\Exception\NotFoundException;

/**
 * @internal
 */
class KeyNotFoundException extends \RuntimeException implements NotFoundException
{
    /**
     * @param string               $key
     * @param \ReflectionProperty  $property
     * @param KeyNotFoundException $prev
     * @return KeyNotFoundException
     */
    public static function unresolvedProperty($key, \ReflectionProperty $property, KeyNotFoundException $prev)
    {
        $reflectionClass = $property->getDeclaringClass();

        return new KeyNotFoundException(sprintf(
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
     * @param KeyNotFoundException $prev
     * @return KeyNotFoundException
     */
    public static function unresolvedParameter($key, \ReflectionParameter $parameter, KeyNotFoundException $prev)
    {
        $reflectionFunction = $parameter->getDeclaringFunction();
        $reflectionClass = $parameter->getDeclaringClass();

        $source = $reflectionClass
            ? sprintf('%s::%s()', $reflectionClass->name, $reflectionFunction->name)
            : $reflectionFunction->name;

        return new KeyNotFoundException(sprintf(
            'Error while resolving "%s" from "%s" in %s:%d',
            $key,
            $source,
            $reflectionFunction->getFileName(),
            $reflectionFunction->getStartLine()
        ), 0, $prev);
    }
}
