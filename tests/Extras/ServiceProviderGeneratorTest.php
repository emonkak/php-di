<?php

namespace Emonkak\Di\Tests\Extras;

use Emonkak\Di\Container;
use Emonkak\Di\Extras\ServiceProviderGenerator;
use Pimple\Container as Pimple;

/**
 * @covers Emonkak\Di\Extras\ServiceProviderGenerator
 */
class ServiceProviderGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $container = Container::create();
        $generator = ServiceProviderGenerator::create();

        do {
            $className = 'Class_' . md5(mt_rand());
        } while (class_exists($className));

        $dependency = $container->resolve('Emonkak\Di\Tests\Extras\Stubs\Foo');

        $source = $generator->generate($className, $dependency);

        $this->assertInternalType('string', $source);

        eval($source);

        $this->assertTrue(class_exists($className));

        $serviceProvider = new $className();

        $this->assertInstanceOf('Pimple\ServiceProviderInterface', $serviceProvider);

        $pimple = new Pimple();
        $pimple->register($serviceProvider);

        $foo = $pimple['Emonkak\Di\Tests\Extras\Stubs\Foo'];
        $this->assertInstanceOf('Emonkak\Di\Tests\Extras\Stubs\Foo', $foo);
        $this->assertInstanceOf('Emonkak\Di\Tests\Extras\Stubs\Bar', $foo->bar);
        $this->assertSame('optional', $foo->optional1);
        $this->assertSame(123, $foo->optional2);
    }
}

