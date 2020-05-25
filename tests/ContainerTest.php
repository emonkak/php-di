<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Container;
use Emonkak\Di\Inspector\InspectorInterface;
use Emonkak\Di\Instantiator\InstantiatorInterface;
use Emonkak\Di\Module;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\Container
 */
class ContainerTest extends TestCase
{
    private InspectorInterface $inspector;

    private InstantiatorInterface $instantiator;

    private Container $container;

    public function setUp(): void
    {
        $this->container = new Container(
            $this->inspector = $this->createMock(InspectorInterface::class),
            $this->instantiator = $this->createMock(InstantiatorInterface::class)
        );
    }

    public function testCreateDefault(): void
    {
        $this->assertInstanceOf(Container::class, Container::createDefault());
    }

    public function testGet(): void
    {
        $key = 'key';
        $bindings = [$key => $this->createMock(BindingInterface::class)];
        $dependency = new \stdClass();
        $instance = new \stdClass();

        $this->inspector
            ->expects($this->once())
            ->method('inspect')
            ->with($this->identicalTo($key), $this->identicalTo($bindings))
            ->willReturn($dependency);
        $this->instantiator
            ->expects($this->once())
            ->method('instantiate')
            ->with($this->identicalTo($dependency), $this->identicalTo($bindings))
            ->willReturn($instance);

        $this->container->merge(new Module($bindings));

        $this->assertSame($instance, $this->container->get($key));
    }

    public function testHas(): void
    {
        $key = 'key';
        $bindings = [$key => $this->createMock(BindingInterface::class)];

        $this->container->merge(new Module($bindings));

        $this->assertTrue($this->container->has($key));
        $this->assertTrue($this->container->has(\DateTime::class));
        $this->assertFalse($this->container->has('invalid key'));
    }
}
