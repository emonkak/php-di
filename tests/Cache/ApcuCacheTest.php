<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests\Cache;

use Emonkak\Di\Cache\ApcuCache;
use PHPUnit\Framework\TestCase;

/**
 * @requires extension apcu
 *
 * @covers \Emonkak\Di\Cache\ApcuCache
 */
class ApcuCacheTest extends TestCase
{
    public function test()
    {
        $cache = new ApcuCache('prefix.');

        $cache['a'] = 123;
        $cache['b'] = 456;

        $this->assertSame(123, $cache['a']);
        $this->assertSame(456, $cache['b']);
        $this->assertNull($cache['c']);

        $this->assertTrue(isset($cache['a']));
        $this->assertTrue(isset($cache['b']));
        $this->assertFalse(isset($cache['c']));

        unset($cache['a']);
        unset($cache['b']);
        unset($cache['c']);

        $this->assertFalse(isset($cache['a']));
        $this->assertFalse(isset($cache['b']));
        $this->assertFalse(isset($cache['c']));
    }
}
