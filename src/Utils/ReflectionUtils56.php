<?php

namespace Emonkak\Di\Utils;

class ReflectionUtils56 extends ReflectionUtils50
{
    private function __construct() {}

    /**
     * @param string $className
     * @param array  $args
     * @return mixed
     */
    public static function newInstance($className, array $args)
    {
        return new $className(...$args);
    }

    /**
     * @param string  $instance
     * @param string  $method
     * @param mixed[] $args
     * @return mixed
     */
    public static function callMethod($instance, $method, array $args)
    {
        return $instance->$method(...$args);
    }

    /**
     * @param callable $function
     * @param mixed[]  $args
     * @return mixed
     */
    public static function callFunction(callable $function, array $args)
    {
        return $function(...$args);
    }
}
