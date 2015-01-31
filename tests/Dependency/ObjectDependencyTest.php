<?php

namespace Emonkak\Di\Tests\Dependency
{
    use Emonkak\Di\Container;
    use Emonkak\Di\Dependency\ObjectDependency;
    use Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Bar;
    use Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Baz;
    use Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Foo;
    use Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Qux;

    class ObjectDependencyTest extends \PHPUnit_Framework_TestCase
    {
        public function testAccept()
        {
            $dependency = new ObjectDependency('foo', 'stdClass', [], [], []);

            $visitor = $this->getMock('Emonkak\Di\Dependency\DependencyVisitorInterface');
            $visitor
                ->expects($this->once())
                ->method('visitObjectDependency')
                ->willReturn($expectedValue = new \stdClass());

            $this->assertSame($expectedValue, $dependency->accept($visitor));
        }

        public function testDependencies()
        {
            $container = Container::create();

            $barDependency = new ObjectDependency(
                'bar',
                'Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Bar',
                [], [], []
            );
            $bazDependency = new ObjectDependency(
                'baz',
                'Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Baz',
                [], [], []);
            $quxDependency = new ObjectDependency(
                'qux',
                'Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Qux',
                [], [], []
            );
            $fooDependency = new ObjectDependency(
                'foo',
                'Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Foo',
                [$barDependency], ['setBaz' => [$bazDependency]], ['qux' => $quxDependency]
            );

            $this->assertSame([$barDependency, $bazDependency, $quxDependency], $fooDependency->getDependencies());
        }

        public function testKey()
        {
            $dependency = new ObjectDependency('foo', 'stdClass', [], [], []);

            $this->assertSame('foo', $dependency->getKey());
        }

        public function testInject()
        {
            $container = Container::create();

            $barDependency = new ObjectDependency(
                'bar',
                'Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Bar',
                [], [], []
            );
            $bazDependency = new ObjectDependency(
                'baz',
                'Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Baz',
                [], [], []);
            $quxDependency = new ObjectDependency(
                'qux',
                'Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Qux',
                [], [], []
            );
            $fooDependency = new ObjectDependency(
                'foo',
                'Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Foo',
                [$barDependency], ['setBaz' => [$bazDependency]], ['qux' => $quxDependency]
            );

            $foo = $fooDependency->inject($container);

            $this->assertInstanceOf('Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Foo', $foo);
            $this->assertInstanceOf('Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Bar', $foo->bar);
            $this->assertInstanceOf('Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Baz', $foo->baz);
            $this->assertInstanceOf('Emonkak\Di\Tests\Dependency\ObjectDependencyTest\Qux', $foo->qux);
        }

        public function testIsSingleton()
        {
            $dependency = new ObjectDependency('foo', 'stdClass', [], [], []);

            $this->assertFalse($dependency->isSingleton());
        }

        public function testTraverse()
        {
            $callback = $this->getMock('stdClass', ['__invoke']);

            $parameter1 = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');
            $parameter1
                ->expects($this->once())
                ->method('traverse')
                ->with($this->identicalTo($callback));
            $parameter2 = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');
            $parameter2
                ->expects($this->once())
                ->method('traverse')
                ->with($this->identicalTo($callback));
            $parameter3 = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');
            $parameter3
                ->expects($this->once())
                ->method('traverse')
                ->with($this->identicalTo($callback));

            $dependency = new ObjectDependency(
                'foo',
                'stdClass',
                [$parameter1], ['setBaz' => [$parameter2]], ['qux' => $parameter3]
            );


            $callback
                ->expects($this->once())
                ->method('__invoke')
                ->with($this->identicalTo($dependency), 'foo');

            $dependency->traverse($callback);
        }

        public function testGetClassName()
        {
            $dependency = new ObjectDependency('foo', 'stdClass', [], [], []);

            $this->assertSame('stdClass', $dependency->getClassName());
        }

        public function testGetConstructorParameters()
        {
            $paramerters = [$this->getMock('Emonkak\Di\Dependency\DependencyInterface')];
            $dependency = new ObjectDependency('foo', 'stdClass', $paramerters, [], []);

            $this->assertSame($paramerters, $dependency->getConstructorParameters());
        }

        public function testGetMethodInjections()
        {
            $methodInjections = ['setBar' => $this->getMock('Emonkak\Di\Dependency\DependencyInterface')];
            $dependency = new ObjectDependency('foo', 'stdClass', [], $methodInjections, []);

            $this->assertSame($methodInjections, $dependency->getMethodInjections());
        }

        public function testGetPropertyInjections()
        {
            $propertyInjections = ['qux' => $this->getMock('Emonkak\Di\Dependency\DependencyInterface')];
            $dependency = new ObjectDependency('foo', 'stdClass', [], [], $propertyInjections);

            $this->assertSame($propertyInjections, $dependency->getPropertyInjections());
        }
    }
}

namespace Emonkak\Di\Tests\Dependency\ObjectDependencyTest
{
    class Foo
    {
        public $bar;
        public $baz;
        public $qux;

        public function __construct(Bar $bar)
        {
            $this->bar = $bar;
        }

        public function setBaz(Baz $baz)
        {
            $this->baz = $baz;
        }
    }

    class Bar
    {
    }

    class Baz
    {
    }

    class Qux
    {
    }
}
