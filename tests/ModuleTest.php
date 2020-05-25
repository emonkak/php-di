<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Binding\Factory;
use Emonkak\Di\Binding\Implementation;
use Emonkak\Di\Binding\Singleton;
use Emonkak\Di\Binding\Value;
use Emonkak\Di\Module;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\Module
 */
class ModuleTest extends TestCase
{
    public function testAddBinding(): void
    {
        $key = 'key';
        $binding = $this->createMock(BindingInterface::class);
        $module = new Module();
        $this->assertSame($binding, $module->addBinding($key, $binding));
        $this->assertSame([$key => $binding], $module->getBindings());
    }

    public function testMerge(): void
    {
        $foo = $this->createMock(BindingInterface::class);
        $bar = $this->createMock(BindingInterface::class);
        $baz = $this->createMock(BindingInterface::class);

        $firstModule = new Module();
        $firstModule->addBinding('first', $foo);
        $firstModule->addBinding('second', $bar);

        $secondModule = new Module();
        $secondModule->addBinding('first', $baz);

        $this->assertSame(['first' => $baz, 'second' => $bar], $firstModule->merge($secondModule)->getBindings());
    }

    public function testBind(): void
    {
        $key = \DateTime::class;
        $expectedBinding = new Implementation($key);

        $module = new Module();

        $this->assertEquals($expectedBinding, $module->bind($key));
        $this->assertEquals([$key => $expectedBinding], $module->getBindings());
    }

    public function testBindSingleton(): void
    {
        $key = \DateTime::class;
        $expectedBinding = new Singleton(new Implementation($key));

        $module = new Module();

        $this->assertEquals($expectedBinding, $module->bindSingleton($key));
        $this->assertEquals([$key => $expectedBinding], $module->getBindings());
    }

    public function testImplement(): void
    {
        $key = \DateTimeInterface::class;
        $className = \DateTime::class;
        $expectedBinding = new Implementation($className);

        $module = new Module();

        $this->assertEquals($expectedBinding, $module->implement($key, $className));
        $this->assertEquals([$key => $expectedBinding], $module->getBindings());
    }

    public function testImplementSingleton(): void
    {
        $key = \DateTimeInterface::class;
        $className = \DateTime::class;
        $expectedBinding = new Singleton(new Implementation($className));

        $module = new Module();

        $this->assertEquals($expectedBinding, $module->implementSingleton($key, $className));
        $this->assertEquals([$key => $expectedBinding], $module->getBindings());
    }

    public function testProvide(): void
    {
        $key = \DateTimeInterface::class;
        $factoryFunction = function() {
            return new \DateTime();
        };
        $expectedBinding = new Factory($factoryFunction);

        $module = new Module();

        $this->assertEquals($expectedBinding, $module->provide($key, $factoryFunction));
        $this->assertEquals([$key => $expectedBinding], $module->getBindings());
    }

    public function testProvideSingleton(): void
    {
        $key = \DateTimeInterface::class;
        $factoryFunction = function() {
            return new \DateTime();
        };
        $expectedBinding = new Singleton(new Factory($factoryFunction));

        $module = new Module();

        $this->assertEquals($expectedBinding, $module->provideSingleton($key, $factoryFunction));
        $this->assertEquals([$key => $expectedBinding], $module->getBindings());
    }

    public function testSet(): void
    {
        $key = \DateTimeInterface::class;
        $value = new \DateTime('1970-01-01');
        $expectedBinding = new Value($value);

        $module = new Module();

        $this->assertEquals($expectedBinding, $module->set($key, $value));
        $this->assertEquals([$key => $expectedBinding], $module->getBindings());
    }
}
