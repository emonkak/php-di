<?php

namespace Emonkak\Di\Tests\Utils
{
    use Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Functions;
    use Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Lambda;
    use Emonkak\Di\Utils\ReflectionUtils;

    class ReflectionUtilsTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideGetFunction
         */
        public function testGetFunction(callable $f)
        {
            $this->assertInstanceof('ReflectionFunctionAbstract', ReflectionUtils::getFunction($f));
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
            $this->assertInstanceof($class, ReflectionUtils::newInstance($class, $args));
        }

        /**
         * @dataProvider provideNewInstance
         */
        public function testnewInstanceWithReflection($class, $args)
        {
            $this->assertInstanceof($class, ReflectionUtils::newInstanceWithReflection($class, $args));
        }

        public function provideNewInstance()
        {
            return [
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Zero',   []],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\One',    [1]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Two',    [1, 2]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Three',  [1, 2, 3]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Four',   [1, 2, 3, 4]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Five',   [1, 2, 3, 4, 5]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Six',    [1, 2, 3, 4, 5, 6]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Seven',  [1, 2, 3, 4, 5, 6, 7]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Eight',  [1, 2, 3, 4, 5, 6, 7, 8]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Nine',   [1, 2, 3, 4, 5, 6, 7, 8, 9]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Ten',    [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
                ['Emonkak\Di\Tests\Utils\ReflectionUtilsTest\Eleven', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]],
            ];
        }

        /**
         * @dataProvider provideCallMethod
         */
        public function testCallMethod($instance, $method, $args)
        {
            ReflectionUtils::callMethod($instance, $method, $args);
        }

        /**
         * @dataProvider provideCallMethod
         */
        public function testCallMethodWithReflection($instance, $method, $args)
        {
            ReflectionUtils::callMethodWithReflection($instance, $method, $args);
        }

        /**
         * @dataProvider provideCallMethod
         */
        public function testCallFunction($instance, $method, $args)
        {
            ReflectionUtils::callFunction([$instance, $method], $args);
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
}

namespace Emonkak\Di\Tests\Utils\ReflectionUtilsTest
{
    class Lambda
    {
        public function __invoke()
        {
        }
    }

    class Functions
    {
        public function zero()
        {
        }

        public function one($a)
        {
        }

        public function two($a, $b)
        {
        }

        public function three($a, $b, $c)
        {
        }

        public function four($a, $b, $c, $d)
        {
        }

        public function five($a, $b, $c, $d, $e)
        {
        }

        public function six($a, $b, $c, $d, $e, $f)
        {
        }

        public function seven($a, $b, $c, $d, $e, $f, $g)
        {
        }

        public function eight($a, $b, $c, $d, $e, $f, $g, $h)
        {
        }

        public function nine($a, $b, $c, $d, $e, $f, $g, $h, $i)
        {
        }

        public function ten($a, $b, $c, $d, $e, $f, $g, $h, $i, $j)
        {
        }

        public function eleven($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k)
        {
        }
    }

    class Zero
    {
        public function __construct()
        {
        }
    }

    class One
    {
        public function __construct($a)
        {
        }
    }

    class Two
    {
        public function __construct($a, $b)
        {
        }
    }

    class Three
    {
        public function __construct($a, $b, $c)
        {
        }
    }

    class Four
    {
        public function __construct($a, $b, $c, $d)
        {
        }
    }

    class Five
    {
        public function __construct($a, $b, $c, $d, $e)
        {
        }
    }

    class Six
    {
        public function __construct($a, $b, $c, $d, $e, $f)
        {
        }
    }

    class Seven
    {
        public function __construct($a, $b, $c, $d, $e, $f, $g)
        {
        }
    }

    class Eight
    {
        public function __construct($a, $b, $c, $d, $e, $f, $g, $h)
        {
        }
    }

    class Nine
    {
        public function __construct($a, $b, $c, $d, $e, $f, $g, $h, $i)
        {
        }
    }

    class Ten
    {
        public function __construct($a, $b, $c, $d, $e, $f, $g, $h, $i, $j)
        {
        }
    }

    class Eleven
    {
        public function __construct($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k)
        {
        }
    }
}
