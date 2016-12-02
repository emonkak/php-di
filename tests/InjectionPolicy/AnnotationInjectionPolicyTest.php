<?php

namespace Emonkak\Di\Tests\InjectionPolicy;

use Doctrine\Common\Annotations\AnnotationReader;
use Emonkak\Di\Annotation\Inject;
use Emonkak\Di\Annotation\Qualifier;
use Emonkak\Di\Annotation\Scope;
use Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Scope\SingletonScope;

/**
 * @covers Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy
 */
class AnnotationInjectionPolicyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->fallback = $this->createMock(InjectionPolicyInterface::class);
        $this->injectionPolicy = new AnnotationInjectionPolicy($this->fallback, new AnnotationReader());
    }

    public function testCreate()
    {
        $this->assertInstanceOf(AnnotationInjectionPolicy::class, AnnotationInjectionPolicy::create());
    }

    public function testGetInjectableMethods()
    {
        $serviceClass = new \ReflectionClass(AnnotatedFooService::class);

        $this->fallback
            ->expects($this->once())
            ->method('getInjectableMethods')
            ->with($this->identicalTo($serviceClass))
            ->willReturn([]);

        $actual = [$serviceClass->getMethod('setQux')];
        $expected = $this->injectionPolicy->getInjectableMethods($serviceClass);

        $this->assertCount(count($actual), $expected);
        $this->assertSame((string) $actual[0], (string) $expected[0]);
    }

    public function testGetInjectableProperties()
    {
        $serviceClass = new \ReflectionClass(AnnotatedFooService::class);

        $this->fallback
            ->expects($this->once())
            ->method('getInjectableProperties')
            ->with($this->identicalTo($serviceClass))
            ->willReturn([]);

        $actual = [$serviceClass->getProperty('foo'), $serviceClass->getProperty('quux')];
        $expected = $this->injectionPolicy->getInjectableProperties($serviceClass);

        $this->assertCount(count($actual), $expected);
        $this->assertSame((string) $actual[0], (string) $expected[0]);
    }

    public function testGetParameterKey()
    {
        $serviceClass = new \ReflectionClass(AnnotatedFooService::class);

        $constructor = $serviceClass->getConstructor();
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
        $fooProperty = new \ReflectionProperty(AnnotatedFooService::class, 'foo');
        $quuxProperty = new \ReflectionProperty(AnnotatedFooService::class, 'quux');

        $this->fallback
            ->expects($this->once())
            ->method('getPropertyKey')
            ->with($this->identicalTo($quuxProperty))
            ->willReturn('$quux');

        $this->assertSame('$named_foo', $this->injectionPolicy->getPropertyKey($fooProperty));
        $this->assertSame('$quux', $this->injectionPolicy->getPropertyKey($quuxProperty));
    }

    public function testGetScope()
    {
        $fooServiceClass = new \ReflectionClass(AnnotatedFooService::class);
        $barServiceClass = new \ReflectionClass(AnnotatedBarService::class);
        $bazServiceClass = new \ReflectionClass(AnnotatedBazService::class);

        $this->fallback
            ->expects($this->once())
            ->method('getScope')
            ->with($this->identicalTo($bazServiceClass))
            ->willReturn(PrototypeScope::getInstance());

        $this->assertInstanceOf(SingletonScope::class, $this->injectionPolicy->getScope($fooServiceClass));
        $this->assertInstanceOf(PrototypeScope::class, $this->injectionPolicy->getScope($barServiceClass));
        $this->assertInstanceOf(PrototypeScope::class, $this->injectionPolicy->getScope($bazServiceClass));
    }

    public function testIsInjectableClass()
    {
        $fooServiceClass = new \ReflectionClass(AnnotatedFooService::class);
        $barServiceClass = new \ReflectionClass(AnnotatedBarService::class);
        $bazServiceClass = new \ReflectionClass(AnnotatedBazService::class);

        $this->fallback
            ->expects($this->once())
            ->method('isInjectableClass')
            ->willReturn(false);

        $this->assertTrue($this->injectionPolicy->isInjectableClass($fooServiceClass));
        $this->assertTrue($this->injectionPolicy->isInjectableClass($barServiceClass));
        $this->assertFalse($this->injectionPolicy->isInjectableClass($bazServiceClass));
    }
}

/**
 * @Inject
 * @Scope(Scope::SINGLETON)
 */
class AnnotatedFooService
{
    /**
     * @Inject
     * @Qualifier("$named_foo")
     */
    public $foo;

    public $bar;

    public $baz;

    public $qux;

    /**
     * @Inject
     */
    public $quux;

    /**
     * @Qualifier(bar="$named_bar")
     */
    public function __construct($bar, $baz)
    {
        $this->bar = $bar;
        $this->baz = $baz;
    }

    /**
     * @Inject
     */
    public function setQux($qux)
    {
        $this->qux = $qux;
    }
}

/**
 * @Inject
 * @Scope(Scope::PROTOTYPE)
 */
class AnnotatedBarService
{
    private function __construct()
    {
    }
}

class AnnotatedBazService
{
}
