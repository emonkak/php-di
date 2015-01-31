<?php

namespace Emonkak\Di\Tests\Extras
{
    use Emonkak\Di\Extras\ServiceProviderGenerator;
    use Emonkak\Di\Container;
    use Pimple\Container as Pimple;

    class ServiceProviderGeneratorTest extends \PHPUnit_Framework_TestCase
    {
        public function testGenerate()
        {
            $container = Container::create();
            $generator = ServiceProviderGenerator::create();

            do {
                $className = 'Class_' . md5(mt_rand());
            } while (class_exists($className));

            $dependency = $container->get('Emonkak\Di\Tests\Extras\ServiceProviderGeneratorTest\Foo');

            $source = $generator->generate($className, $dependency);

            $this->assertInternalType('string', $source);

            eval($source);

            $this->assertTrue(class_exists($className));

            $serviceProvider = new $className();

            $this->assertInstanceOf('Pimple\ServiceProviderInterface', $serviceProvider);

            $pimple = new Pimple();
            $pimple->register($serviceProvider);

            $this->assertInstanceOf('Emonkak\Di\Tests\Extras\ServiceProviderGeneratorTest\Foo', $pimple['Emonkak\Di\Tests\Extras\ServiceProviderGeneratorTest\Foo']);
        }
    }
}

namespace Emonkak\Di\Tests\Extras\ServiceProviderGeneratorTest
{
    class Foo
    {
        public function __construct(Bar $bar, Baz $baz)
        {
            $this->bar = $bar;
            $this->baz = $baz;
        }
    }

    class Bar
    {
        public function __construct(Baz $baz)
        {
            $this->baz = $baz;
        }
    }

    class Baz
    {
    }
}
