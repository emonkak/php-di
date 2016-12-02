<?php

namespace Emonkak\Di\Tests\Definition;

use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Tests\Fixtures\Bar;
use Emonkak\Di\Tests\Fixtures\BarInterface;
use Emonkak\Di\Tests\Fixtures\Baz;
use Emonkak\Di\Tests\Fixtures\BazInterface;
use Emonkak\Di\Tests\Fixtures\Foo;
use Emonkak\Di\Tests\Fixtures\FooInterface;

/**
 * @covers Emonkak\Di\Definition\BindingDefinition
 */
class BindingDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveBy()
    {
        $serviceClass = new \ReflectionClass(BindingDefinitionTestService::class);

        $fooDependency = $this->createMock(DependencyInterface::class);
        $barDependency = $this->createMock(DependencyInterface::class);
        $bazDependency = $this->createMock(DependencyInterface::class);

        $scope = $this->createMock(ScopeInterface::class);
        $scope
            ->expects($this->once())
            ->method('get')
            ->will($this->returnArgument(0));

        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->exactly(2))
            ->method('resolveParameter')
            ->withConsecutive(
                $serviceClass->getConstructor()->getParameters(),
                $serviceClass->getMethod('setBar')->getParameters()
            )
            ->will($this->onConsecutiveCalls(
                $fooDependency,
                $barDependency
            ));
        $resolver
            ->expects($this->once())
            ->method('resolveProperty')
            ->with($serviceClass->getProperty('baz'))
            ->willReturn($bazDependency);

        $injectionPolicy = $this->createMock(InjectionPolicyInterface::class);
        $injectionPolicy
            ->expects($this->once())
            ->method('isInjectableClass')
            ->with($serviceClass)
            ->willReturn(true);
        $injectionPolicy
            ->expects($this->once())
            ->method('getInjectableMethods')
            ->with($serviceClass)
            ->willReturn([$serviceClass->getMethod('setBar')]);
        $injectionPolicy
            ->expects($this->once())
            ->method('getInjectableProperties')
            ->with($serviceClass)
            ->willReturn([$serviceClass->getProperty('baz')]);
        $injectionPolicy
            ->expects($this->once())
            ->method('getScope')
            ->with($serviceClass)
            ->willReturn($scope);

        $dependency = (new BindingDefinition(BindingDefinitionTestServiceInterface::class))
            ->to($serviceClass->name)
            ->resolveBy($resolver, $injectionPolicy);

        $this->assertInstanceOf(ObjectDependency::class, $dependency);
        $this->assertSame(BindingDefinitionTestServiceInterface::class, $dependency->getKey());
        $this->assertSame(BindingDefinitionTestService::class, $dependency->getClassName());
        $this->assertSame([$fooDependency], $dependency->getConstructorDependencies());
        $this->assertSame(['setBar' => [$barDependency]], $dependency->getMethodDependencies());
        $this->assertSame(['baz' => $bazDependency], $dependency->getPropertyDependencies());
    }

    public function testResolveByWithPreDefining()
    {
        $serviceClass = new \ReflectionClass(BindingDefinitionTestService::class);

        $scope = $this->createMock(ScopeInterface::class);
        $scope
            ->expects($this->once())
            ->method('get')
            ->will($this->returnArgument(0));

        $resolver = $this->createMock(ResolverInterface::class);

        $injectionPolicy = $this->createMock(InjectionPolicyInterface::class);
        $injectionPolicy
            ->expects($this->once())
            ->method('isInjectableClass')
            ->with($serviceClass)
            ->willReturn(true);
        $injectionPolicy
            ->expects($this->once())
            ->method('getInjectableMethods')
            ->with($serviceClass)
            ->willReturn([]);
        $injectionPolicy
            ->expects($this->once())
            ->method('getInjectableProperties')
            ->with($serviceClass)
            ->willReturn([]);
        $injectionPolicy
            ->expects($this->once())
            ->method('getScope')
            ->with($serviceClass)
            ->willReturn($scope);

        $fooDependency = $this->createMock(DependencyInterface::class);
        $barDependency = $this->createMock(DependencyInterface::class);
        $bazDependency = $this->createMock(DependencyInterface::class);

        $fooDefinition = $this->createMock(DefinitionInterface::class);
        $fooDefinition
            ->expects($this->once())
            ->method('resolveBy')
            ->with($this->identicalTo($resolver), $this->identicalTo($injectionPolicy))
            ->willReturn($fooDependency);
        $barDefinition = $this->createMock(DefinitionInterface::class);
        $barDefinition
            ->expects($this->once())
            ->method('resolveBy')
            ->with($this->identicalTo($resolver), $this->identicalTo($injectionPolicy))
            ->willReturn($barDependency);
        $bazDefinition = $this->createMock(DefinitionInterface::class);
        $bazDefinition
            ->expects($this->once())
            ->method('resolveBy')
            ->with($this->identicalTo($resolver), $this->identicalTo($injectionPolicy))
            ->willReturn($bazDependency);

        $dependency = (new BindingDefinition(BindingDefinitionTestServiceInterface::class))
            ->to($serviceClass->name)
            ->with([$fooDefinition])
            ->withMethod('setBar', [$barDefinition])
            ->withProperty('baz', $bazDefinition)
            ->resolveBy($resolver, $injectionPolicy);

        $this->assertInstanceOf(ObjectDependency::class, $dependency);
        $this->assertSame(BindingDefinitionTestServiceInterface::class, $dependency->getKey());
        $this->assertSame(BindingDefinitionTestService::class, $dependency->getClassName());
        $this->assertSame([$fooDependency], $dependency->getConstructorDependencies());
        $this->assertSame(['setBar' => [$barDependency]], $dependency->getMethodDependencies());
        $this->assertSame(['baz' => $bazDependency], $dependency->getPropertyDependencies());
    }

    /**
     * @expectedException Emonkak\Di\Exception\UninjectableClassException
     */
    public function testResolveByThrowsUninjectableClassException()
    {
        $serviceClass = new \ReflectionClass(BindingDefinitionTestService::class);

        $resolver = $this->createMock(ResolverInterface::class);

        $injectionPolicy = $this->createMock(InjectionPolicyInterface::class);
        $injectionPolicy
            ->expects($this->once())
            ->method('isInjectableClass')
            ->with($serviceClass)
            ->willReturn(false);

        (new BindingDefinition($serviceClass->name))
            ->resolveBy($resolver, $injectionPolicy);
    }
}

class BindingDefinitionTestService implements BindingDefinitionTestServiceInterface
{
    private $baz;

    public function __construct(FooInterface $foo)
    {
    }

    public function setBar(BarInterface $bar)
    {
    }
}

interface BindingDefinitionTestServiceInterface
{
}
