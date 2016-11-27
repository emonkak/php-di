<?php

namespace Emonkak\Di\Tests\Definition;

use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;

/**
 * @covers Emonkak\Di\Definition\AliasDefinition
 */
class AliasDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveBy()
    {
        $definition = new AliasDefinition(\stdClass::class);
        $dependency = $this->getMock(DependencyInterface::class);

        $container = $this->getMock(ResolverInterface::class);
        $container
            ->expects($this->once())
            ->method('resolve')
            ->with(\stdClass::class)
            ->willReturn($dependency);
        $injectionPolicy = $this->getMock(InjectionPolicyInterface::class);

        $this->assertSame($dependency, $definition->resolveBy($container, $injectionPolicy));
    }
}
