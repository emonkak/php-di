<?php

namespace Emonkak\Di\Tests\Annotation;

use Emonkak\Di\Annotation\Qualifier;

/**
 * @covers Emonkak\Di\Annotation\Qualifier
 */
class QualifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideGetValue
     */
    public function testGetValue(array $values, $name, $expectedValue)
    {
        $qualifier = new Qualifier($values);
        $this->assertSame($expectedValue, $qualifier->getValue($name));
    }

    public function provideGetValue()
    {
        return [
            [['foo' => 'bar'],            'foo', 'bar'],
            [['foo' => \stdClass::class], 'foo', 'stdClass'],
            [[],                          'foo', null],
        ];
    }

    /**
     * @dataProvider provideGetSingleValue
     */
    public function testGetSingleValue(array $values, $expectedValue)
    {
        $qualifier = new Qualifier($values);
        $this->assertSame($expectedValue, $qualifier->getSingleValue());
    }

    public function provideGetSingleValue()
    {
        return [
            [['value' => 'bar'],            'bar'],
            [['value' => \stdClass::class], 'stdClass'],
            [['foo' => 'bar'],               null],
            [[],                             null],
        ];
    }
}
