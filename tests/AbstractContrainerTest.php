<?php

namespace Emonkak\Di\Tests
{
    use Emonkak\Di\Scope\SingletonScope;

    abstract class AbstractContrainerTest extends \PHPUnit_Framework_TestCase
    {
        public function setUp()
        {
            $this->container = $this->prepareContainer();
            $this->container
                ->bind('Emonkak\Di\Tests\AbstractContrainerTest\BarInterface')
                ->to('Emonkak\Di\Tests\AbstractContrainerTest\Bar');
            $this->container
                ->bind('Emonkak\Di\Tests\AbstractContrainerTest\BazInterface')
                ->to('Emonkak\Di\Tests\AbstractContrainerTest\Baz')
                ->in(SingletonScope::getInstance());
            $this->container->alias('$piyo', '$payo');
            $this->container->set('$payo', 'payo');
            $this->container->factory('$poyo', function() {
                return 'poyo';
            });
            $this->container->set('$hoge', function() {
            });
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

        public function testGetInjectionFinder()
        {
            $this->assertInstanceOf('Emonkak\Di\InjectionFinder', $this->container->getInjectionFinder());
        }

        public function testGetInjectionPolicy()
        {
            $this->assertInstanceOf('Emonkak\Di\InjectionPolicy\InjectionPolicyInterface', $this->container->getInjectionPolicy());
        }

        public function testGet()
        {
            $fooDependency = $this->container->get('Emonkak\Di\Tests\AbstractContrainerTest\Foo');

            $this->assertInstanceOf('Emonkak\Di\Dependency\ObjectDependency', $fooDependency);

            $foo = $fooDependency->materialize($this->container);

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
         * @expectedException InvalidArgumentException
         */
        public function testGetThrowsInvalidArgumentException()
        {
            $this->container->get('Emonkak\Di\Tests\AbstractContrainerTest\FooInterface');
        }

        public function testGetInstance()
        {
            $foo = $this->container->getInstance('Emonkak\Di\Tests\AbstractContrainerTest\Foo');

            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Foo', $foo);
            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Bar', $foo->bar);
            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Baz', $foo->bar->baz);
            $this->assertInstanceOf('Emonkak\Di\Tests\AbstractContrainerTest\Baz', $foo->baz);
            $this->assertSame($foo->baz, $foo->bar->baz);
            $this->assertSame('payo', $foo->baz->piyo);
            $this->assertSame('payo', $foo->baz->payo);
            $this->assertSame('poyo', $foo->baz->poyo);

            $this->assertInstanceOf('stdClass', $this->container->getInstance('stdClass'));
        }

        abstract protected function prepareContainer();
    }
}

namespace Emonkak\Di\Tests\AbstractContrainerTest
{
    use Emonkak\Di\Annotation\Inject;
    use Emonkak\Di\Annotation\Qualifier;

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
