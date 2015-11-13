<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\FlyweightFactoryDependency;

class FlyweightFactoryDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testFrom()
    {
        $original = new FactoryDependency(
            'foo',
            function() {},
            [$this->getMock('Emonkak\Di\Dependency\DependencyInterface')]
        );
        $new = FlyweightFactoryDependency::from($original);

        $this->assertInstanceOf('Emonkak\Di\Dependency\FlyweightFactoryDependency', $new);
        $this->assertSame($original->getKey(), $new->getKey());
        $this->assertSame($original->getFactory(), $new->getFactory());
        $this->assertSame($original->getParameters(), $new->getParameters());
    }

    public function testMaterializeBy()
    {
        $container = Container::create();

        $factory = $this->getMock('stdClass', ['__invoke']);
        $factory
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn($expectedValue = new \stdClass());

        $dependency = new FlyweightFactoryDependency('foo', $factory, []);

        $this->assertSame($expectedValue, $dependency->materializeBy($container));
        $this->assertSame($expectedValue, $dependency->materializeBy($container));
    }

    public function testIsSingleton()
    {
        $dependency = new FlyweightFactoryDependency('foo', function() {}, []);

        $this->assertTrue($dependency->isSingleton());
    }
}
