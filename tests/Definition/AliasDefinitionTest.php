<?php

namespace Emonkak\Di\Tests\Definition;

use Emonkak\Di\Definition\AliasDefinition;

class AliasDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        \Closure::bind(function() {
            $definition = new AliasDefinition('stdClass');
            $dependency = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');

            $container = $this->getMock('Emonkak\Di\ContainerInterface');
            $container
                ->expects($this->once())
                ->method('get')
                ->with($this->identicalTo('stdClass'))
                ->willReturn($dependency);

            $this->assertSame($dependency, $definition->resolve($container));
        }, $this, 'Emonkak\Di\Definition\AliasDefinition')->__invoke();
    }

    public function testResolveScope()
    {
        \Closure::bind(function() {
            $definition = new AliasDefinition('stdClass');
            $container = $this->getMock('Emonkak\Di\ContainerInterface');

            $this->assertInstanceOf('Emonkak\Di\Scope\PrototypeScope', $definition->resolveScope($container));
        }, $this, 'Emonkak\Di\Definition\AliasDefinition')->__invoke();
    }
}
