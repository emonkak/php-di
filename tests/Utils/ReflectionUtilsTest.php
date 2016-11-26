<?php

namespace Emonkak\Di\Tests\Utils;

use Emonkak\Di\Tests\Utils\Stubs\Functions;
use Emonkak\Di\Tests\Utils\Stubs\Lambda;
use Emonkak\Di\Utils\ReflectionUtils50;
use Emonkak\Di\Utils\ReflectionUtils56;

class ReflectionUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideGetFunction
     */
    public function testGetFunction(callable $f)
    {
        $this->assertInstanceof('ReflectionFunctionAbstract', ReflectionUtils50::getFunction($f));
    }

    public function provideGetFunction()
    {
        return [
            [[$this, 'assertEquals']],
            [function() {}],
            ['var_dump'],
            [new Lambda()],
        ];
    }

    /**
     * @dataProvider provideNewInstance
     */
    public function testNewInstance($class, $args)
    {
        $this->assertInstanceof($class, ReflectionUtils50::newInstance($class, $args));
    }

    /**
     * @requires PHP 5.6
     * @dataProvider provideNewInstance
     */
    public function testNewInstance56($class, $args)
    {
        $this->assertInstanceof($class, ReflectionUtils56::newInstance($class, $args));
    }

    /**
     * @dataProvider provideNewInstance
     */
    public function testnewInstanceWithReflection($class, $args)
    {
        $this->assertInstanceof($class, ReflectionUtils50::newInstanceWithReflection($class, $args));
    }

    public function provideNewInstance()
    {
        return [
            ['Emonkak\Di\Tests\Utils\Stubs\Zero',   []],
            ['Emonkak\Di\Tests\Utils\Stubs\One',    [1]],
            ['Emonkak\Di\Tests\Utils\Stubs\Two',    [1, 2]],
            ['Emonkak\Di\Tests\Utils\Stubs\Three',  [1, 2, 3]],
            ['Emonkak\Di\Tests\Utils\Stubs\Four',   [1, 2, 3, 4]],
            ['Emonkak\Di\Tests\Utils\Stubs\Five',   [1, 2, 3, 4, 5]],
            ['Emonkak\Di\Tests\Utils\Stubs\Six',    [1, 2, 3, 4, 5, 6]],
            ['Emonkak\Di\Tests\Utils\Stubs\Seven',  [1, 2, 3, 4, 5, 6, 7]],
            ['Emonkak\Di\Tests\Utils\Stubs\Eight',  [1, 2, 3, 4, 5, 6, 7, 8]],
            ['Emonkak\Di\Tests\Utils\Stubs\Nine',   [1, 2, 3, 4, 5, 6, 7, 8, 9]],
            ['Emonkak\Di\Tests\Utils\Stubs\Ten',    [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            ['Emonkak\Di\Tests\Utils\Stubs\Eleven', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]],
        ];
    }

    /**
     * @dataProvider provideCallMethod
     */
    public function testCallMethod($instance, $method, $args)
    {
        ReflectionUtils50::callMethod($instance, $method, $args);
    }

    /**
     * @requires PHP 5.6
     * @dataProvider provideCallMethod
     */
    public function testCallMethod56($instance, $method, $args)
    {
        ReflectionUtils56::callMethod($instance, $method, $args);
    }

    /**
     * @dataProvider provideCallMethod
     */
    public function testCallMethodWithReflection($instance, $method, $args)
    {
        ReflectionUtils50::callMethodWithReflection($instance, $method, $args);
    }

    /**
     * @dataProvider provideCallMethod
     */
    public function testCallFunction($instance, $method, $args)
    {
        ReflectionUtils50::callFunction([$instance, $method], $args);
    }

    /**
     * @requires PHP 5.6
     * @dataProvider provideCallMethod
     */
    public function testCallFunction56($instance, $method, $args)
    {
        ReflectionUtils56::callFunction([$instance, $method], $args);
    }

    public function provideCallMethod()
    {
        return [
            [new Functions(), 'zero',   []],
            [new Functions(), 'one',    [1]],
            [new Functions(), 'two',    [1, 2]],
            [new Functions(), 'three',  [1, 2, 3]],
            [new Functions(), 'four',   [1, 2, 3, 4]],
            [new Functions(), 'five',   [1, 2, 3, 4, 5]],
            [new Functions(), 'six',    [1, 2, 3, 4, 5, 6]],
            [new Functions(), 'seven',  [1, 2, 3, 4, 5, 6, 7]],
            [new Functions(), 'eight',  [1, 2, 3, 4, 5, 6, 7, 8]],
            [new Functions(), 'nine',   [1, 2, 3, 4, 5, 6, 7, 8, 9]],
            [new Functions(), 'ten',    [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            [new Functions(), 'eleven', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]],
        ];
    }
}
