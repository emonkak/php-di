<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests\Inspector;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Inspector\CachedInspector;
use Emonkak\Di\Inspector\InspectorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\Inspector\CachedInspector
 */
class CachedInspectorTest extends TestCase
{
    public function testInspect(): void
    {
        $key = 'key';
        $bindings = [
            $key => $this->createMock(BindingInterface::class),
        ];
        $dependency = new \stdClass();
        $cache = new \ArrayObject();

        $inspector = $this->createMock(InspectorInterface::class);
        $inspector
            ->expects($this->once())
            ->method('inspect')
            ->with($this->identicalTo($key), $this->identicalTo($bindings))
            ->willReturn($dependency);

        $cachedInspector = new CachedInspector($inspector, $cache);

        $this->assertSame($dependency, $cachedInspector->inspect($key, $bindings));
        $this->assertSame($dependency, $cachedInspector->inspect($key, $bindings));
    }

    public function testGetCache(): void
    {
        $inspector = $this->createMock(InspectorInterface::class);
        $cache = new \ArrayObject();

        $cachedInspector = new CachedInspector($inspector, $cache);

        $this->assertSame($cache, $cachedInspector->getCache());
    }
}
