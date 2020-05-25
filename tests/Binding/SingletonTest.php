<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests\Binding;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Binding\Singleton;
use Emonkak\Di\Instantiator\InstantiatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\Binding\Singleton
 */
class SingletonTest extends TestCase
{
    public function testResolve(): void
    {
        $bindings = [
            'key' => $this->createMock(BindingInterface::class),
        ];
        $dependencies = [
            (object) ['id' => 1],
            (object) ['id' => 2],
            (object) ['id' => 3],
        ];
        $instance = new \stdClass();

        $instantiator = $this->createMock(InstantiatorInterface::class);

        $binding = $this->createMock(BindingInterface::class);
        $binding
            ->expects($this->once())
            ->method('resolve')
            ->with($this->identicalTo($dependencies), $this->identicalTo($bindings), $this->identicalTo($instantiator))
            ->willReturn($instance);

        $singleton = new Singleton($binding);

        $this->assertEquals($instance, $singleton->resolve($dependencies, $bindings, $instantiator));
        $this->assertEquals($instance, $singleton->resolve($dependencies, $bindings, $instantiator));
    }

    public function testGetFunction(): void
    {
        $function = $this->createMock(\ReflectionFunctionAbstract::class);

        $binding = $this->createMock(BindingInterface::class);
        $binding
            ->expects($this->once())
            ->method('getFunction')
            ->willReturn($function);

        $singleton = new Singleton($binding);

        $this->assertSame($function, $singleton->getFunction());
    }
}
