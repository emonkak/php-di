<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\PimpleContainerWrapper;
use Pimple\Container as Pimple;

/**
 * @covers Emonkak\Di\PimpleContainerWrapper
 */
class PimpleContainerWrapperTest extends \PHPUnit_Framework_TestCase
{
    public function testAsArrayAccess()
    {
        $obj = new \stdClass();
        $f = function() {};

        $container = new PimpleContainerWrapper(new Pimple());
        $container['foo'] = $obj;
        $container['bar'] = $f;

        $this->assertSame($obj, $container['foo']);
        $this->assertSame($f, $container['bar']);

        $this->assertTrue(isset($container['foo']));
        $this->assertTrue(isset($container['bar']));
        $this->assertFalse(isset($container['baz']));

        unset($container['foo']);
        unset($container['bar']);

        $this->assertFalse(isset($container['foo']));
        $this->assertFalse(isset($container['bar']));
    }
}
