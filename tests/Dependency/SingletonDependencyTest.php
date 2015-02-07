<?php

namespace Emonkak\Di\Tests\Dependency
{
    use Emonkak\Di\Container;
    use Emonkak\Di\Dependency\ObjectDependency;
    use Emonkak\Di\Dependency\SingletonDependency;
    use Emonkak\Di\Tests\Dependency\SingletonDependencyTest\Bar;
    use Emonkak\Di\Tests\Dependency\SingletonDependencyTest\Baz;
    use Emonkak\Di\Tests\Dependency\SingletonDependencyTest\Foo;
    use Emonkak\Di\Tests\Dependency\SingletonDependencyTest\Qux;

    class SingletonDependencyTest extends \PHPUnit_Framework_TestCase
    {
        public function testFrom()
        {
            $original = new ObjectDependency(
                'foo',
                'stdClass',
                [$this->getMock('Emonkak\Di\Dependency\DependencyInterface')],
                ['setBaz' => $this->getMock('Emonkak\Di\Dependency\DependencyInterface')],
                ['qux' => $this->getMock('Emonkak\Di\Dependency\DependencyInterface')]
            );
            $new = SingletonDependency::from($original);

            $this->assertInstanceOf('Emonkak\Di\Dependency\SingletonDependency', $new);
            $this->assertSame($original->getKey(), $new->getKey());
            $this->assertSame($original->getClassName(), $new->getClassName());
            $this->assertSame($original->getMethodInjections(), $new->getMethodInjections());
            $this->assertSame($original->getPropertyInjections(), $new->getPropertyInjections());
        }

        public function testMaterialize()
        {
            $container = Container::create();

            $dependency = new SingletonDependency(
                'stdClass',
                'stdClass',
                [], [], []
            );

            $obj = $dependency->materialize($container);

            $this->assertSame($obj, $dependency->materialize($container));
        }

        public function testIsSingleton()
        {
            $dependency = new SingletonDependency('foo', 'stdClass', [], [], []);

            $this->assertTrue($dependency->isSingleton());
        }
    }
}

namespace Emonkak\Di\Tests\Dependency\SingletonDependencyTest
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
