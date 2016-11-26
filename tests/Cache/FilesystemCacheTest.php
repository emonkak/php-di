<?php

namespace Emonkak\Di\Tests\Cache;

use Emonkak\Di\Cache\FilesystemCache;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers Emonkak\Di\Cache\FilesystemCache
 */
class FilesystemCacheTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->fooDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid(__CLASS__);
        $this->barDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid(__CLASS__);
    }

    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove([$this->fooDir, $this->barDir]);
    }

    public function test()
    {
        $fooCache = FilesystemCache::create($this->fooDir);
        $barCache = FilesystemCache::create($this->barDir);

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
