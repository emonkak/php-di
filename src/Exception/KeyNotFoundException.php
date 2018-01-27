<?php

namespace Emonkak\Di\Exception;

use Emonkak\Di\Internal\Reflectors;
use Interop\Container\Exception\NotFoundException as InteropNotFoundException;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundExceptionInterface;

/**
 * @internal
 */
class KeyNotFoundException extends \RuntimeException implements PsrNotFoundExceptionInterface, InteropNotFoundException
{
    /**
     * @param \ReflectionProperty  $property
     * @param KeyNotFoundException $prev
     * @return KeyNotFoundException
     */
    public static function unresolvedProperty(\ReflectionProperty $property, KeyNotFoundException $prev)
    {
        $reflectionClass = $property->getDeclaringClass();

        return new KeyNotFoundException(sprintf(
            'Error while resolving the property "%s::$%s"',
            $reflectionClass->name,
            $property->name
        ), 0, $prev);
    }

    /**
     * @param \ReflectionParameter $parameter
     * @param KeyNotFoundException $prev
     * @return KeyNotFoundException
     */
    public static function unresolvedParameter(\ReflectionParameter $parameter, KeyNotFoundException $prev)
    {
        $reflectionFunction = $parameter->getDeclaringFunction();
        $reflectionClass = $parameter->getDeclaringClass();

        $typeHint = Reflectors::getTypeHint($parameter);
        $parameterName = ($typeHint !== null ? $typeHint . ' ' : '') . '$' . $parameter->name;
        $functionName = $reflectionClass
            ? sprintf('%s::%s()', $reflectionClass->name, $reflectionFunction->name)
            : sprintf('%s()', $reflectionFunction->name);

        return new KeyNotFoundException(sprintf(
            'Error while resolving the parameter "%s" from function "%s"',
            $parameterName,
            $functionName
        ), 0, $prev);
    }
}
