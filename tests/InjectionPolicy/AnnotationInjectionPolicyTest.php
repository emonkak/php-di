<?php

namespace Emonkak\Di\Tests\InjectionPolicy;

use Doctrine\Common\Annotations\AnnotationReader;
use Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Scope\SingletonScope;
use Emonkak\Di\Tests\InjectionPolicy\Stubs\Bar;
use Emonkak\Di\Tests\InjectionPolicy\Stubs\Baz;
use Emonkak\Di\Tests\InjectionPolicy\Stubs\Foo;

/**
 * @covers Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy
 */
class AnnotationInjectionPolicyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->fallback = $this->getMock(InjectionPolicyInterface::class);
        $this->injectionPolicy = new AnnotationInjectionPolicy($this->fallback, new AnnotationReader());
    }

    public function testCreate()
    {
        $this->assertInstanceOf(AnnotationInjectionPolicy::class, AnnotationInjectionPolicy::create());
    }

    public function testGetInjectableMethods()
    {
        $fooClass = new \ReflectionClass(Foo::class);

        $this->fallback
            ->expects($this->once())
            ->method('getInjectableMethods')
            ->with($this->identicalTo($fooClass))
            ->willReturn([]);

        $actual = [$fooClass->getMethod('setQux')];
        $expected = $this->injectionPolicy->getInjectableMethods($fooClass);

        $this->assertCount(count($actual), $expected);
        $this->assertSame((string) $actual[0], (string) $expected[0]);
    }

    public function testGetInjectableProperties()
    {
        $fooClass = new \ReflectionClass(Foo::class);

        $this->fallback
            ->expects($this->once())
            ->method('getInjectableProperties')
            ->with($this->identicalTo($fooClass))
            ->willReturn([]);

        $actual = [$fooClass->getProperty('foo'), $fooClass->getProperty('foobar')];
        $expected = $this->injectionPolicy->getInjectableProperties($fooClass);

        $this->assertCount(count($actual), $expected);
        $this->assertSame((string) $actual[0], (string) $expected[0]);
    }

    public function testGetParameterKey()
    {
        $fooClass = new \ReflectionClass(Foo::class);

        $constructor = $fooClass->getConstructor();
        $paramerters = $constructor->getParameters();

        $this->fallback
            ->expects($this->once())
            ->method('getParameterKey')
            ->with($this->identicalTo($paramerters[1]))
            ->willReturn('$baz');

        $this->assertSame('$named_bar', $this->injectionPolicy->getParameterKey($paramerters[0]));
        $this->assertSame('$baz', $this->injectionPolicy->getParameterKey($paramerters[1]));
    }

    public function testGetPropertyKey()
    {
        $fooProperty = new \ReflectionProperty(Foo::class, 'foo');
        $foobarProperty = new \ReflectionProperty(Foo::class, 'foobar');

        $this->fallback
            ->expects($this->once())
            ->method('getPropertyKey')
            ->with($this->identicalTo($foobarProperty))
            ->willReturn('$foobar');

        $this->assertSame('$named_foo', $this->injectionPolicy->getPropertyKey($fooProperty));
        $this->assertSame('$foobar', $this->injectionPolicy->getPropertyKey($foobarProperty));
    }

    public function testGetScope()
    {
        $fooClass = new \ReflectionClass(Foo::class);
        $barClass = new \ReflectionClass(Bar::class);
        $bazClass = new \ReflectionClass(Baz::class);

        $this->fallback
            ->expects($this->once())
            ->method('getScope')
            ->with($this->identicalTo($bazClass))
            ->willReturn(PrototypeScope::getInstance());

        $this->assertInstanceOf(SingletonScope::class, $this->injectionPolicy->getScope($fooClass));
        $this->assertInstanceOf(PrototypeScope::class, $this->injectionPolicy->getScope($barClass));
        $this->assertInstanceOf(PrototypeScope::class, $this->injectionPolicy->getScope($bazClass));
    }

    public function testIsInjectableClass()
    {
        $fooClass = new \ReflectionClass(Foo::class);
        $barClass = new \ReflectionClass(Bar::class);
        $bazClass = new \ReflectionClass(Baz::class);

        $this->fallback
            ->expects($this->once())
            ->method('isInjectableClass')
            ->willReturn(false);

        $this->assertTrue($this->injectionPolicy->isInjectableClass($fooClass));
        $this->assertTrue($this->injectionPolicy->isInjectableClass($barClass));
        $this->assertFalse($this->injectionPolicy->isInjectableClass($bazClass));
    }
}
