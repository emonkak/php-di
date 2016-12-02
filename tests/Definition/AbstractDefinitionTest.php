<?php

namespace Emonkak\Di\Tests\Definition;

use Emonkak\Di\Definition\AbstractDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;
use Emonkak\Di\Scope\ScopeInterface;

/**
 * @covers Emonkak\Di\Definition\AbstractDefinition
 */
class AbstractDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $injectionPolicy = $this->createMock(InjectionPolicyInterface::class);

        $dependency = $this->createMock(DependencyInterface::class);

        $scope = $this->createMock(ScopeInterface::class);
        $scope
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo($dependency))
            ->will($this->returnArgument(0));

        $definition = $this->getMockForAbstractClass(AbstractDefinition::class);
        $definition
            ->expects($this->once())
            ->method('resolveDependency')
            ->with($this->identicalTo($resolver), $this->identicalTo($injectionPolicy))
            ->willReturn($dependency);
        $definition
            ->expects($this->once())
            ->method('resolveScope')
            ->with($this->identicalTo($resolver), $this->identicalTo($injectionPolicy))
            ->willReturn($scope);

        $this->assertSame($dependency, $definition->resolveBy($resolver, $injectionPolicy));
    }

    public function testIn()
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $injectionPolicy = $this->createMock(InjectionPolicyInterface::class);

        $dependency = $this->createMock(DependencyInterface::class);

        $scope = $this->createMock(ScopeInterface::class);
        $scope
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo($dependency))
            ->will($this->returnArgument(0));

        $definition = $this->getMockForAbstractClass(AbstractDefinition::class);
        $definition
            ->expects($this->once())
            ->method('resolveDependency')
            ->with($this->identicalTo($resolver), $this->identicalTo($injectionPolicy))
            ->willReturn($dependency);
        $definition
            ->expects($this->never())
            ->method('resolveScope');

        $this->assertSame($dependency, $definition->in($scope)->resolveBy($resolver, $injectionPolicy));
    }
}
