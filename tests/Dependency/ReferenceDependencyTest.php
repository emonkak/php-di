<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;

/**
 * @covers Emonkak\Di\Dependency\ReferenceDependency
 */
class ReferenceDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIterator()
    {
        $dependency = new ReferenceDependency('foo');

        $this->assertEquals(['foo' => $dependency], iterator_to_array($dependency));
    }

    public function testAccept()
    {
        $dependency = new ReferenceDependency('foo');

        $visitor = $this->createMock(DependencyVisitorInterface::class);
        $visitor
            ->expects($this->once())
            ->method('visitReferenceDependency')
            ->willReturn($expectedValue = new \stdClass());

        $this->assertSame($expectedValue, $dependency->accept($visitor));
    }

    public function testResolveBy()
    {
        $injectionPolicy = $this->createMock(InjectionPolicyInterface::class);
        $resolver = $this->createMock(ResolverInterface::class);
        $dependency = new ReferenceDependency('foo');

        $this->assertSame($dependency, $dependency->resolveBy($resolver, $injectionPolicy));
    }

    public function testGetDependencies()
    {
        $dependency = new ReferenceDependency('foo');

        $this->assertEmpty($dependency->getDependencies());
    }

    public function testGetKey()
    {
        $dependency = new ReferenceDependency('foo');

        $this->assertSame('foo', $dependency->getKey());
    }

    public function testInstantiateBy()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('get')
            ->willReturn(123);

        $dependency = new ReferenceDependency('foo');

        $this->assertSame(123, $dependency->instantiateBy($container));
    }
}
