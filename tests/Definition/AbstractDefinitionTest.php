<?php

namespace Emonkak\Di\Tests\Definition;

class AbstractDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $container = $this->getMock('Emonkak\Di\ContainerInterface');
        $injectionPolicy = $this->getMock('Emonkak\Di\InjectionPolicy\InjectionPolicyInterface');

        $dependency = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');

        $scope = $this->getMock('Emonkak\Di\Scope\ScopeInterface');
        $scope
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo($dependency))
            ->willReturn($dependency);

        $definition = $this->getMockForAbstractClass('Emonkak\Di\Definition\AbstractDefinition');
        $definition
            ->expects($this->once())
            ->method('resolveDependency')
            ->with($this->identicalTo($container), $this->identicalTo($injectionPolicy))
            ->willReturn($dependency);
        $definition
            ->expects($this->once())
            ->method('resolveScope')
            ->with($this->identicalTo($container), $this->identicalTo($injectionPolicy))
            ->willReturn($scope);

        $this->assertSame($dependency, $definition->resolveBy($container, $injectionPolicy));
    }

    public function testGetAndIn()
    {
        $container = $this->getMock('Emonkak\Di\ContainerInterface');
        $injectionPolicy = $this->getMock('Emonkak\Di\InjectionPolicy\InjectionPolicyInterface');

        $dependency = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');

        $scope = $this->getMock('Emonkak\Di\Scope\ScopeInterface');
        $scope
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo($dependency))
            ->willReturn($dependency);

        $definition = $this->getMockForAbstractClass('Emonkak\Di\Definition\AbstractDefinition');
        $definition
            ->expects($this->once())
            ->method('resolveDependency')
            ->with($this->identicalTo($container), $this->identicalTo($injectionPolicy))
            ->willReturn($dependency);
        $definition
            ->expects($this->never())
            ->method('resolveScope');

        $this->assertSame($dependency, $definition->in($scope)->resolveBy($container, $injectionPolicy));
    }
}
