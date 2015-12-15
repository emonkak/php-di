<?php

namespace Emonkak\Di\Exception;

use Interop\Container\Exception\NotFoundException as InteropNotFoundException;

class NotFoundException extends \Exception implements InteropNotFoundException
{
    /**
     * @param string                   $key
     * @param \ReflectionProperty      $property
     * @param InteropNotFoundException $prev
     */
    public static function ofProperty($key, \ReflectionProperty $property, InteropNotFoundException $prev)
    {
        $reflectionClass = $property->getDeclaringClass();

        return new NotFoundException(sprintf(
            'Error while resolving "%s" from "%s" in %s:%d',
            $key,
            $reflectionClass->name,
            $reflectionClass->getFileName(),
            $reflectionClass->getStartLine()
        ), 0, $prev);
    }

    /**
     * @param string                   $key
     * @param \ReflectionParameter     $parameter
     * @param InteropNotFoundException $prev
     */
    public static function ofParameter($key, \ReflectionParameter $parameter, InteropNotFoundException $prev)
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

    /**
     * @return string
     */
    public function __toString()
    {
        $message = [];

        $e = $this;
        while ($e instanceof InteropNotFoundException) {
            $messages[] = $e->getMessage();
            $e = $e->getPrevious();
        }

        return implode("\n", $messages);
    }
}
