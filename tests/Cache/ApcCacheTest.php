<?php

namespace Emonkak\Di\Tests\Cache;

use Emonkak\Di\Cache\ApcCache;

/**
 * @requires extension apc
 *
 * @covers Emonkak\Di\Cache\ApcCache
 */
class ApcCacheTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $fooCache = new ApcCache('foo.');
        $barCache = new ApcCache('bar.');

        $fooCache['x'] = 123;
        $fooCache['y'] = 456;
        $barCache['z'] = 789;

        $this->assertSame(123, $fooCache['x']);
        $this->assertSame(456, $fooCache['y']);
        $this->assertSame(789, $barCache['z']);

        $this->assertTrue(isset($fooCache['x']));
        $this->assertTrue(isset($fooCache['y']));
        $this->assertFalse(isset($fooCache['z']));

        $this->assertFalse(isset($barCache['x']));
        $this->assertFalse(isset($barCache['y']));
        $this->assertTrue(isset($barCache['z']));

        unset($fooCache['x']);
        unset($fooCache['y']);
        unset($barCache['z']);

        $this->assertFalse(isset($fooCache['x']));
        $this->assertFalse(isset($fooCache['y']));
        $this->assertFalse(isset($fooCache['z']));

        $this->assertFalse(isset($barCache['x']));
        $this->assertFalse(isset($barCache['y']));
        $this->assertFalse(isset($barCache['z']));
    }
}
