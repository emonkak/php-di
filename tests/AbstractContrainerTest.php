<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\AbstractContainer;
use Emonkak\Di\Annotation\Inject;
use Emonkak\Di\Annotation\Qualifier;
use Emonkak\Di\ContainerConfiguratorInterface;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\Scope\SingletonScope;
use Emonkak\Di\Tests\Fixtures\Bar;
use Emonkak\Di\Tests\Fixtures\BarInterface;
use Emonkak\Di\Tests\Fixtures\Baz;
use Emonkak\Di\Tests\Fixtures\BazInterface;
use Emonkak\Di\Tests\Fixtures\Foo;
use Emonkak\Di\Tests\Fixtures\FooInterface;

abstract class AbstractContrainerTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = $this->prepareContainer();
    }

    public function testConfigure()
    {
        $configurator = $this->getMock(ContainerConfiguratorInterface::class);
        $configurator
            ->expects($this->once())
            ->method('configure')
            ->with($this->identicalTo($this->container));

        $this->container->configure($configurator);
    }

    public function testResolve()
    {
        $dependency = $this->container->resolve(Foo::class);
        $this->assertInstanceOf(ObjectDependency::class, $dependency);
    }

    /**
     * @expectedException Emonkak\Di\Exception\KeyNotFoundException
     */
    public function testResolveThrowsKeyNotFoundException()
    {
        $this->container->resolve(FooService::class);
    }

    public function testResolveParameterDependency()
    {
        $serviceClass = new \ReflectionClass(FooService::class);
        $parameters = $serviceClass->getConstructor()->getParameters();

        $this->assertEquals(new ValueDependency(BazService::class, null), $this->container->resolveParameter($parameters[1]));
    }

    /**
     * @expectedException Emonkak\Di\Exception\KeyNotFoundException
     */
    public function testResolveParameterDependencyThrowsKeyNotFoundException()
    {
        $serviceClass = new \ReflectionClass(BarService::class);
        $parameters = $serviceClass->getConstructor()->getParameters();

        $this->container->resolveParameter($parameters[0]);
    }

    public function testResolvePropertyDependency()
    {
        $serviceClass = new \ReflectionClass(FooService::class);

        $this->assertEquals(new ValueDependency(BazInterface::class, 'baz'), $this->container->resolveProperty($serviceClass->getProperty('baz')));
    }

    /**
     * @expectedException Emonkak\Di\Exception\KeyNotFoundException
     */
    public function testResolvePropertyDependencyThrowsKeyNotFoundException()
    {
        $serviceClass = new \ReflectionClass(FooService::class);

        $this->container->resolveProperty($serviceClass->getProperty('barService'));
    }

    public function testGet()
    {
        $this->container->configure(new FooBundle());

        $service = $this->container->get(FooService::class);

        $this->assertInstanceOf(FooService::class, $service);
        $this->assertInstanceOf(BarService::class, $service->barService);
        $this->assertInstanceOf(Baz::class, $service->barService->baz);
        $this->assertInstanceOf(Foo::class, $service->bazService->foo);
        $this->assertInstanceOf(Bar::class, $service->bazService->bar);
        $this->assertInstanceOf(Baz::class, $service->bazService->baz);
        $this->assertInstanceOf(Baz::class, $service->baz);
        $this->assertSame($service->bazService->baz, $service->baz);
    }

    public function testHas()
    {
        $this->assertTrue($this->container->has(Foo::class));
        $this->assertFalse($this->container->has(FooInterface::class));
    }

    public function testInstantiate()
    {
        $this->container->configure(new FooBundle());

        $dependency = $this->container->resolve(FooService::class);
        $service = $this->container->instantiate($dependency);

        $this->assertInstanceOf(FooService::class, $service);
        $this->assertInstanceOf(BarService::class, $service->barService);
        $this->assertInstanceOf(Baz::class, $service->barService->baz);
        $this->assertInstanceOf(Foo::class, $service->bazService->foo);
        $this->assertInstanceOf(Bar::class, $service->bazService->bar);
        $this->assertInstanceOf(Baz::class, $service->bazService->baz);
        $this->assertInstanceOf(Baz::class, $service->baz);
        $this->assertSame($service->bazService->baz, $service->baz);
    }

    abstract protected function prepareContainer();
}

class FooService
{
    public $barService;

    public $bazService;

    /**
     * @Inject
     * @Qualifier(BazInterface::class)
     */
    public $baz = 'baz';

    public function __construct(BarService $barService, BazService $bazService = null)
    {
        $this->barService = $barService;
        $this->bazService = $bazService;
    }
}

class BarService
{
    public $baz;

    public function __construct(BazInterface $baz)
    {
        $this->baz = $baz;
    }
}

class BazService
{
    public $foo;

    public $bar;

    public $baz;

    /**
     * @Inject
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @Inject
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    /**
     * @Inject
     */
    public function setBaz($baz)
    {
        $this->baz = $baz;
    }
}

class FooBundle implements ContainerConfiguratorInterface
{
    public function configure(AbstractContainer $container)
    {
        $container
            ->bind(FooInterface::class)
            ->to(Bar::class);
        $container
            ->bind(BarInterface::class)
            ->to(Bar::class);
        $container
            ->bind(BazInterface::class)
            ->to(Baz::class)
            ->in(SingletonScope::getInstance());
        $container->factory('$foo', function() {
            return new Foo();
        });
        $container->set('$bar', new Bar());
        $container->alias('$baz', BazInterface::class);
    }
}
