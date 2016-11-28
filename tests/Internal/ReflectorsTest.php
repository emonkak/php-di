<?php

namespace Emonkak\Di\Tests\Internal;

use Emonkak\Di\Tests\Fixtures\Lambda;
use Emonkak\Di\Internal\Reflectors;

class ReflectorsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideGetFunction
     */
    public function testGetFunction(callable $f)
    {
        $this->assertInstanceof(\ReflectionFunctionAbstract::class, Reflectors::getFunction($f));
    }

    public function provideGetFunction()
    {
        return [
            [function() {}],
            ['var_dump'],
            [new Lambda()],
            [[new Lambda(), '__invoke']],
        ];
    }

    public function testGetTypeHint()
    {
        $func = function(array $foo , callable $bar, \stdClass $baz, $qux) {};
        $reflection = new \ReflectionFunction($func);
        $parameters = $reflection->getParameters();

        $this->assertSame('array', Reflectors::getTypeHint($parameters[0]));
        $this->assertSame('callable', Reflectors::getTypeHint($parameters[1]));
        $this->assertSame(\stdClass::class, Reflectors::getTypeHint($parameters[2]));
        $this->assertNull(Reflectors::getTypeHint($parameters[3]));
    }
}
