<?php

namespace Emonkak\Di\Internal;

final class Reflectors
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param callable $function
     * @return \ReflectionFunctionAbstract
     */
    public static function getFunction(callable $function)
    {
        if (is_array($function)) {
            return new \ReflectionMethod($function[0], $function[1]);
        }

        if (is_object($function) && !($function instanceof \Closure)) {
            return new \ReflectionMethod($function, '__invoke');
        }

        return new \ReflectionFunction($function);
    }
}
