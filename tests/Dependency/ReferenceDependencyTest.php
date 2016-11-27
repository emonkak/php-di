<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;

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

        $visitor = $this->getMock(DependencyVisitorInterface::class);
        $visitor
            ->expects($this->once())
            ->method('visitReferenceDependency')
            ->willReturn($expectedValue = new \stdClass());

        $this->assertSame($expectedValue, $dependency->accept($visitor));
    }

    public function testResolveBy()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $container = Container::create($injectionPolicy);
        $dependency = new ReferenceDependency('foo');

        $this->assertSame($dependency, $dependency->resolveBy($container, $injectionPolicy));
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

    public function testMaterializeBy()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $cache = new \ArrayObject();
        $pool = new \ArrayObject();
        $container = new Container($injectionPolicy, $cache, $pool);
        $container->set('foo', $expectedValue = new \stdClass());

        $dependency = new ReferenceDependency('foo');

        $this->assertSame($expectedValue, $dependency->materializeBy($container, $pool));
    }
}
