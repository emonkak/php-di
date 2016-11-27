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
}
