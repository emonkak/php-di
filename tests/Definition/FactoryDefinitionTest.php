<?php

use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;
use Emonkak\Di\Tests\Fixtures\BarInterface;
use Emonkak\Di\Tests\Fixtures\BazInterface;
use Emonkak\Di\Tests\Fixtures\FooInterface;
use SuperClosure\SerializableClosure;

/**
 * @covers Emonkak\Di\Definition\FactoryDefinition
 */
class FactoryDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveDependency()
    {
        $factoryMethod = new \ReflectionMethod(FactoryDefinitionTestServiceFactory::class, '__invoke');
        $factoryMethodParameters = $factoryMethod->getParameters();

        $fooDependency = $this->getMock(DependencyInterface::class);
        $barDependency = $this->getMock(DependencyInterface::class);
        $bazDependency = $this->getMock(DependencyInterface::class);

        $resolver = $this->getMock(ResolverInterface::class);
        $resolver
            ->expects($this->exactly(3))
            ->method('resolveParameter')
            ->withConsecutive(
                [$factoryMethodParameters[0]],
                [$factoryMethodParameters[1]],
                [$factoryMethodParameters[2]]
            )
            ->will($this->onConsecutiveCalls(
                $fooDependency,
                $barDependency,
                $bazDependency
            ));

        $injectionPolicy = $this->getMock(InjectionPolicyInterface::class);

        $factory = new FactoryDefinitionTestServiceFactory();
        $dependency = (new FactoryDefinition(FactoryDefinitionTestService::class, $factory))
            ->resolveBy($resolver, $injectionPolicy);

        $this->assertInstanceOf(FactoryDependency::class, $dependency);
        $this->assertSame($factory, $dependency->getFactory());
        $this->assertSame(FactoryDefinitionTestService::class, $dependency->getKey());
        $this->assertSame([$fooDependency, $barDependency, $bazDependency], $dependency->getDependencies());
    }

    public function testResolveDependencyWithPreDefining()
    {
        $resolver = $this->getMock(ResolverInterface::class);
        $injectionPolicy = $this->getMock(InjectionPolicyInterface::class);

        $fooDependency = $this->getMock(DependencyInterface::class);
        $barDependency = $this->getMock(DependencyInterface::class);
        $bazDependency = $this->getMock(DependencyInterface::class);

        $fooDefinition = $this->getMock(DefinitionInterface::class);
        $fooDefinition
            ->expects($this->once())
            ->method('resolveBy')
            ->with($this->identicalTo($resolver), $this->identicalTo($injectionPolicy))
            ->willReturn($fooDependency);
        $barDefinition = $this->getMock(DefinitionInterface::class);
        $barDefinition
            ->expects($this->once())
            ->method('resolveBy')
            ->with($this->identicalTo($resolver), $this->identicalTo($injectionPolicy))
            ->willReturn($barDependency);
        $bazDefinition = $this->getMock(DefinitionInterface::class);
        $bazDefinition
            ->expects($this->once())
            ->method('resolveBy')
            ->with($this->identicalTo($resolver), $this->identicalTo($injectionPolicy))
            ->willReturn($bazDependency);

        $factory = new FactoryDefinitionTestServiceFactory();

        $dependency = (new FactoryDefinition(FactoryDefinitionTestService::class, $factory))
            ->with([$fooDefinition, $barDefinition, $bazDefinition])
            ->resolveBy($resolver, $injectionPolicy);

        $this->assertInstanceOf(FactoryDependency::class, $dependency);
        $this->assertSame($factory, $dependency->getFactory());
        $this->assertSame(FactoryDefinitionTestService::class, $dependency->getKey());
        $this->assertSame([$fooDependency, $barDependency, $bazDependency], $dependency->getDependencies());
    }

    public function testResolveByWithClosure()
    {
        $factory = function() {
            return new FactoryDefinitionTestServive(new Foo(), new Bar(), new Baz());
        };

        $resolver = $this->getMock(ResolverInterface::class);
        $injectionPolicy = $this->getMock(InjectionPolicyInterface::class);

        $dependency = (new FactoryDefinition(FactoryDefinitionTestService::class, $factory))
            ->resolveBy($resolver, $injectionPolicy);

        $this->assertInstanceOf(FactoryDependency::class, $dependency);
        $this->assertInstanceOf(SerializableClosure::class, $dependency->getFactory());
        $this->assertSame(FactoryDefinitionTestService::class, $dependency->getKey());
        $this->assertSame([], $dependency->getDependencies());
    }
}

class FactoryDefinitionTestServive
{
    public function __construct(FooInterface $foo, BarInterface $bar, BazInterface $baz)
    {
    }
}

class FactoryDefinitionTestServiceFactory
{
    public function __invoke(FooInterface $foo, BarInterface $bar, BazInterface $baz)
    {
        return new FactoryDefinitionTestServive($foo, $bar, $baz);
    }
}
