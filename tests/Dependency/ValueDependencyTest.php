<?php

namespace Emonkak\Di\Tests\Dependency;

use Interop\Container\ContainerInterface;
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
        $dependency = new ValueDependency(123);

        $this->assertEquals([$dependency], iterator_to_array($dependency, false));
    }

    public function testAccept()
    {
        $dependency = new ValueDependency(123);

        $visitor = $this->getMock(DependencyVisitorInterface::class);
        $visitor
            ->expects($this->once())
            ->method('visitValueDependency')
            ->willReturn($expectedValue = new \stdClass());

        $this->assertSame($expectedValue, $dependency->accept($visitor));
    }

    public function testResolveBy()
    {
        $injectionPolicy = $this->getMock(InjectionPolicyInterface::class);
        $resolver = $this->getMock(ResolverInterface::class);
        $dependency = new ValueDependency('foo');

        $this->assertSame($dependency, $dependency->resolveBy($resolver, $injectionPolicy));
    }

    public function testGetDependencies()
    {
        $dependency = new ValueDependency(123);

        $this->assertEmpty($dependency->getDependencies());
    }

    public function testGetKey()
    {
        $dependency = new ValueDependency(123);

        $this->assertSame(sha1(serialize(123)), $dependency->getKey());
    }

    public function testGetValue()
    {
        $dependency = new ValueDependency(123);

        $this->assertSame(123, $dependency->getValue());
    }

    public function testInstantiateBy()
    {
        $container = $this->getMock(ContainerInterface::class);
        $pool = new \ArrayObject();

        $dependency = new ValueDependency(123);

        $this->assertSame(123, $dependency->instantiateBy($container, $pool));
    }
}
