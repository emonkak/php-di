<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;

/**
 * @covers Emonkak\Di\Dependency\ValueDependency
 */
class ValueDependencyTest extends \PHPUnit_Framework_TestCase
{
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

    public function testMaterializeBy()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $cache = new \ArrayObject();
        $pool = new \ArrayObject();
        $container = new Container($injectionPolicy, $cache, $pool);

        $dependency = new ValueDependency(123);

        $this->assertSame(123, $dependency->materializeBy($container, $pool));
    }

    public function testIsSingleton()
    {
        $dependency = new ValueDependency(123);

        $this->assertTrue($dependency->isSingleton());
    }

    public function testTraverse()
    {
        $dependency = new ValueDependency(123);

        $callback = $this->getMock('stdClass', ['__invoke']);
        $callback
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo($dependency));

        $dependency->traverse($callback);
    }
}
