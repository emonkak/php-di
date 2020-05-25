<?php

namespace Emonkak\Di;

use Psr\Container\ContainerExceptionInterface;

/**
 * @internal
 */
class ContainerException extends \RuntimeException implements ContainerExceptionInterface
{
    public static function uninstantiableClass(\ReflectionClass $class): self
    {
        return new self("Class `$class->name` is not instantiable.");
    }

    public static function unresolvedParameter(\ReflectionParameter $parameter, \Throwable $previous): self
    {
        $function = $parameter->getDeclaringFunction();
        $declaringClass = $parameter->getDeclaringClass();
        $type = $parameter->hasType() ? $parameter->getType() : null;
        $parameterName = ($type !== null ? $type->getName() . ' ' : '') . '$' . $parameter->name;
        $functionName = $declaringClass !== null
            ? ($declaringClass->name . '::' . $function->name . '()')
            : ($function->name . '()');
        return new self(
            "Error while resolving the parameter `$parameterName` from function `$functionName`.",
            0,
            $previous
        );
    }
}
