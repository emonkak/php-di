<?php

namespace Emonkak\Di\Tests\InjectionPolicy
{
    use Doctrine\Common\Annotations\AnnotationReader;
    use Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy;
    use Emonkak\Di\Scope\PrototypeScope;
    use Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Foo;

    class AnnotationInjectionPolicyTest extends \PHPUnit_Framework_TestCase
    {
        public function setUp()
        {
            $this->fallback = $this->getMock('Emonkak\Di\InjectionPolicy\InjectionPolicyInterface');
            $this->injectionPolicy = new AnnotationInjectionPolicy($this->fallback, new AnnotationReader());
        }

        public function testCreate()
        {
            $this->assertInstanceOf('Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy', AnnotationInjectionPolicy::create());
        }

        public function testGetInjectableMethods()
        {
            $fooClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Foo');

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
            $fooClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Foo');

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
            $fooClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Foo');

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
            $fooProperty = new \ReflectionProperty('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Foo', 'foo');
            $foobarProperty = new \ReflectionProperty('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Foo', 'foobar');

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
            $fooClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Foo');
            $barClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Bar');
            $bazClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Baz');

            $this->fallback
                ->expects($this->once())
                ->method('getScope')
                ->with($this->identicalTo($bazClass))
                ->willReturn(PrototypeScope::getInstance());

            $this->assertInstanceOf('Emonkak\Di\Scope\SingletonScope', $this->injectionPolicy->getScope($fooClass));
            $this->assertInstanceOf('Emonkak\Di\Scope\PrototypeScope', $this->injectionPolicy->getScope($barClass));
            $this->assertInstanceOf('Emonkak\Di\Scope\PrototypeScope', $this->injectionPolicy->getScope($bazClass));
        }

        public function testIsInjectableClass()
        {
            $fooClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Foo');
            $barClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Bar');
            $bazClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest\Baz');

            $this->fallback
                ->expects($this->once())
                ->method('isInjectableClass')
                ->willReturn(false);

            $this->assertTrue($this->injectionPolicy->isInjectableClass($fooClass));
            $this->assertTrue($this->injectionPolicy->isInjectableClass($barClass));
            $this->assertFalse($this->injectionPolicy->isInjectableClass($bazClass));
        }
    }
}

namespace Emonkak\Di\Tests\InjectionPolicy\AnnotationInjectionPolicyTest
{
    use Emonkak\Di\Annotation\Inject;
    use Emonkak\Di\Annotation\Qualifier;
    use Emonkak\Di\Annotation\Scope;

    /**
     * @Inject
     * @Scope(Scope::SINGLETON)
     */
    class Foo
    {
        /**
         * @Inject
         * @Qualifier("$named_foo")
         */
        public $foo;

        /**
         * @Inject
         */
        public $foobar;

        public $bar;

        public $baz;

        public $qux;

        /**
         * @Qualifier(bar="$named_bar")
         */
        public function __construct(Bar $bar, Baz $baz)
        {
            $this->bar = $bar;
            $this->baz = $baz;
        }

        public function setFoo(Foo $foo)
        {
            $this->foo = $foo;
        }

        public function setBar(Bar $bar)
        {
            $this->bar = $bar;
        }

        public function setBaz(Baz $baz)
        {
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
    class Bar
    {
        private function __construct() {}
    }

    class Baz {}
}
