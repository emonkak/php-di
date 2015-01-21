<?php

namespace Emonkak\Di\Utils;

class ReflectionUtils
{
    private function __construct() {}

    /**
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

    /**
     * @return \Closure
     */
    public static function getClosure(callable $function)
    {
        if ($function instanceof \Closure) {
            return $function;
        }

        return self::getFunction($function)->getClosure();
    }

    public static function newInstance($className, array $args)
    {
        switch (count($args)) {
        case 0:
            return new $className();
        case 1:
            return new $className($args[0]);
        case 2:
            return new $className($args[0], $args[1]);
        case 3:
            return new $className($args[0], $args[1], $args[2]);
        case 4:
            return new $className($args[0], $args[1], $args[2], $args[3]);
        case 5:
            return new $className($args[0], $args[1], $args[2], $args[3], $args[4]);
        case 6:
            return new $className($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
        case 7:
            return new $className($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);
        case 8:
            return new $className($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]);
        case 9:
            return new $className($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8]);
        case 10:
            return new $className($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8], $args[9]);
        default:
            return self::newInstanceByReflection($className, $args);
        }
    }

    public static function newInstanceByReflection($className, array $args)
    {
        $class = new \ReflectionClass($className);
        return $class->newInstanceArgs($args);
    }

    public static function callMethod($className, $method, array $args)
    {
        switch (count($args)) {
        case 0:
            return $className->$method();
        case 1:
            return $className->$method($args[0]);
        case 2:
            return $className->$method($args[0], $args[1]);
        case 3:
            return $className->$method($args[0], $args[1], $args[2]);
        case 4:
            return $className->$method($args[0], $args[1], $args[2], $args[3]);
        case 5:
            return $className->$method($args[0], $args[1], $args[2], $args[3], $args[4]);
        case 6:
            return $className->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
        case 7:
            return $className->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);
        case 8:
            return $className->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]);
        case 9:
            return $className->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8]);
        case 10:
            return $className->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8], $args[9]);
        default:
            return self::callMethodByReflection($className, $args);
        }
    }

    public static function callMethodByReflection($instance, $method, array $args)
    {
        $method = new \ReflectionMethod('Emonkak\Collection\Benchmarks\Functions', $method);
        return $method->invokeArgs($instance, $args);
    }

    public static function callFunction(callable $function, array $args)
    {
        switch (count($args)) {
        case 0:
            return $function();
        case 1:
            return $function($args[0]);
        case 2:
            return $function($args[0], $args[1]);
        case 3:
            return $function($args[0], $args[1], $args[2]);
        case 4:
            return $function($args[0], $args[1], $args[2], $args[3]);
        case 5:
            return $function($args[0], $args[1], $args[2], $args[3], $args[4]);
        case 6:
            return $function($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
        case 7:
            return $function($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);
        case 8:
            return $function($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]);
        case 9:
            return $function($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8]);
        case 10:
            return $function($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8], $args[9]);
        default:
            return call_user_func_array($function, $args);
        }
    }
}
