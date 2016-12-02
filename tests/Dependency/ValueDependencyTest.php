<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;

/**
 * @covers Emonkak\Di\Dependency\ValueDependency
 */
class ValueDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testTraverse()
    {
        $dependency = new ValueDependency('foo', 123);

        $this->assertEquals(['foo' => $dependency], iterator_to_array($dependency));
    }

    public function testAccept()
    {
        $dependency = new ValueDependency('foo', 123);

        $visitor = $this->createMock(DependencyVisitorInterface::class);
        $visitor
            ->expects($this->once())
            ->method('visitValueDependency')
            ->willReturn($expectedValue = new \stdClass());

        $this->assertSame($expectedValue, $dependency->accept($visitor));
    }

    public function testResolveBy()
    {
        $injectionPolicy = $this->createMock(InjectionPolicyInterface::class);
        $resolver = $this->createMock(ResolverInterface::class);
        $dependency = new ValueDependency('foo', 123);

        $this->assertSame($dependency, $dependency->resolveBy($resolver, $injectionPolicy));
    }

    public function testGetDependencies()
    {
        $dependency = new ValueDependency('foo', 123);

        $this->assertEmpty($dependency->getDependencies());
    }

    public function testGetKey()
    {
        $dependency = new ValueDependency('foo', 123);

        $this->assertSame('foo', $dependency->getKey());
    }

    public function testGetValue()
    {
        $dependency = new ValueDependency('foo', 123);

        $this->assertSame(123, $dependency->getValue());
    }

    public function testInstantiateBy()
    {
        $container = $this->createMock(ContainerInterface::class);

        $dependency = new ValueDependency('foo', 123);

        $this->assertSame(123, $dependency->instantiateBy($container));
    }
}
