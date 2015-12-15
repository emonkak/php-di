<?php

namespace Emonkak\Di\Tests
{
    use Emonkak\Di\Dependency\DependencyInterface;
    use Emonkak\Di\Tests\AbstractContrainerTest\FooBundle;

    abstract class AbstractContrainerTest extends \PHPUnit_Framework_TestCase
    {
        public function setUp()
        {
            $this->container = $this->prepareContainer();
        }

        public function testConfigure()
        {
            $configurator = $this->getMock('Emonkak\Di\ContainerConfiguratorInterface');
            $configurator
                ->expects($this->once())
                ->method('configure')
                ->with($this->identicalTo($this->container));

            $this->container->configure($configurator);
        }

        public function testResolve()
        {
            $this->container->configure(new FooBundle());

            $fooDependency = $this->container->resolve('Emonkak\Di\Tests\AbstractContrainerTest\Foo');

            $this->assertInstanceOf('Emonkak\Di\Dependency\ObjectDependency', $fooDependency);

            return $fooDependency;
        }

        /**
         * @depends testResolve
         */
        public function testMaterialize(DependencyInterface $fooDependency)
        {
            $this->container->configure(new FooBundle());

            $foo = $this->container->materialize($fooDependency);

            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Foo', $foo);
            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Bar', $foo->bar);
            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Baz', $foo->bar->baz);
            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Baz', $foo->baz);
            $this->assertInstanceOf('Closure', $foo->hoge);
            $this->assertSame($foo->baz, $foo->bar->baz);
            $this->assertSame('payo', $foo->baz->piyo);
            $this->assertSame('payo', $foo->baz->payo);
            $this->assertSame('poyo', $foo->baz->poyo);
        }

        /**
         * @dataProvider provideResolveThrowsNotFoundException
         *
         * @expectedException Interop\Container\Exception\NotFoundException
         */
        public function testResolveThrowsNotFoundException($key)
        {
            $this->container->resolve($key);
        }

        public function provideResolveThrowsNotFoundException()
        {
            return [
                ['Emonkak\Di\Tests\AbstractContrainerTest\Foo'],
                ['Emonkak\Di\Tests\AbstractContrainerTest\Qux']
            ];
        }

        public function testGet()
        {
            $this->container->configure(new FooBundle());

            $foo = $this->container->get('Emonkak\Di\Tests\AbstractContrainerTest\Foo');

            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Foo', $foo);
            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Bar', $foo->bar);
            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Baz', $foo->bar->baz);
            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Baz', $foo->baz);
            $this->assertSame($foo->baz, $foo->bar->baz);
            $this->assertSame('payo', $foo->baz->piyo);
            $this->assertSame('payo', $foo->baz->payo);
            $this->assertSame('poyo', $foo->baz->poyo);

            $this->assertInstanceOf('stdClass', $this->container->get('stdClass'));
        }

        public function testHas()
        {
            $this->container->configure(new FooBundle());

            $this->assertTrue($this->container->has('Emonkak\Di\Tests\AbstractContrainerTest\Foo'));
            $this->assertFalse($this->container->has('Emonkak\Di\Tests\AbstractContrainerTest\FooInterface'));
        }

        abstract protected function prepareContainer();
    }
}

namespace Emonkak\Di\Tests\AbstractContrainerTest
{
    use Emonkak\Di\AbstractContainer;
    use Emonkak\Di\Annotation\Inject;
    use Emonkak\Di\Annotation\Qualifier;
    use Emonkak\Di\ContainerConfiguratorInterface;
    use Emonkak\Di\Scope\SingletonScope;

    class FooBundle implements ContainerConfiguratorInterface
    {
        public function configure(AbstractContainer $container)
        {
            $container
                ->bind('Emonkak\Di\Tests\AbstractContrainerTest\BarInterface')
                ->to('Emonkak\Di\Tests\AbstractContrainerTest\Bar');
            $container
                ->bind('Emonkak\Di\Tests\AbstractContrainerTest\BazInterface')
                ->to('Emonkak\Di\Tests\AbstractContrainerTest\Baz')
                ->in(SingletonScope::getInstance());
            $container->alias('$piyo', '$payo');
            $container->set('$payo', 'payo');
            $container->factory('$poyo', function() {
                return 'poyo';
            });
            $container->set('$hoge', function() {
                return 'hoge';
            });
        }
    }

    class Foo implements FooInterface
    {
        public $bar;
        public $baz;

        /**
         * @Inject
         * @Qualifier("$hoge")
         */
        public $hoge;

        public function __construct(BarInterface $bar, BazInterface $baz)
        {
            $this->bar = $bar;
            $this->baz = $baz;
        }
    }

    class Bar implements BarInterface
    {
        public $baz;

        public function __construct(BazInterface $baz)
        {
            $this->baz = $baz;
        }
    }

    class Baz implements BazInterface
    {
        public $piyo;
        public $payo;
        public $poyo;

        /**
         * @Inject
         * @Qualifier("$piyo")
         */
        public function setPiyo($piyo)
        {
            $this->piyo = $piyo;
        }

        /**
         * @Inject
         * @Qualifier("$payo")
         */
        public function setPayo($payo)
        {
            $this->payo = $payo;
        }

        /**
         * @Inject
         * @Qualifier("$poyo")
         */
        public function setPoyo($poyo)
        {
            $this->poyo = $poyo;
        }
    }

    class Qux
    {
        /**
         * @Inject
         * @Qualifier("$huga")
         */
        public $huga;
    }

    interface FooInterface
    {
    }

    interface BarInterface
    {
    }

    interface BazInterface
    {
    }
}
