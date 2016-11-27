<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\Annotation\Inject;
use Emonkak\Di\Annotation\Qualifier;
use Emonkak\Di\Container;
use Emonkak\Di\ContainerConfiguratorInterface;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy;
use Emonkak\Di\Module;
use Emonkak\Di\Scope\SingletonScope;
use Emonkak\Di\Tests\Fixtures\Bar;
use Emonkak\Di\Tests\Fixtures\BarInterface;
use Emonkak\Di\Tests\Fixtures\Baz;
use Emonkak\Di\Tests\Fixtures\BazInterface;
use Emonkak\Di\Tests\Fixtures\Foo;
use Emonkak\Di\Tests\Fixtures\FooInterface;

/**
 * @covers Emonkak\Di\Container
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = Container::create(AnnotationInjectionPolicy::create());
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Container::class, Container::create());
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
        $this->container->merge(new FooModule());

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
        $this->container->merge(new FooModule());

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

class FooModule extends Module
{
    public function __construct()
    {
        $this
            ->bind(FooInterface::class)
            ->to(Bar::class);
        $this
            ->bind(BarInterface::class)
            ->to(Bar::class);
        $this
            ->bind(BazInterface::class)
            ->to(Baz::class)
            ->in(SingletonScope::getInstance());
        $this->factory('$foo', function() {
            return new Foo();
        });
        $this->set('$bar', new Bar());
        $this->alias('$baz', BazInterface::class);
    }
}
