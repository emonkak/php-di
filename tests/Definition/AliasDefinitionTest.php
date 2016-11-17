<?php

namespace Emonkak\Di\Tests\Definition;

use Emonkak\Di\Definition\AliasDefinition;

class AliasDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveBy()
    {
        $definition = new AliasDefinition('stdClass');
        $dependency = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');

        $container = $this->getMock('Emonkak\Di\ContainerInterface');
        $container
            ->expects($this->once())
            ->method('resolve')
            ->with($this->identicalTo('stdClass'))
            ->willReturn($dependency);
        $injectionPolicy = $this->getMock('Emonkak\Di\InjectionPolicy\InjectionPolicyInterface');

        $this->assertSame($dependency, $definition->resolveBy($container, $injectionPolicy));
    }
}
