<?php

namespace Emonkak\Di\Tests\InjectionPolicy
{
    use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
    use Emonkak\Di\Tests\InjectionPolicy\DefaultInjectionPolicyTest\Foo;

    class DefaultInjectionPolicyTest extends \PHPUnit_Framework_TestCase
    {
        public function testGetInjectableMethods()
        {
            $injectionPolicy = new DefaultInjectionPolicy();
            $reflectionClass = new \ReflectionClass('stdClass');

            $this->assertEmpty($injectionPolicy->getInjectableMethods($reflectionClass));
        }

        public function testGetInjectableProperties()
        {
            $injectionPolicy = new DefaultInjectionPolicy();
            $reflectionClass = new \ReflectionClass('stdClass');

            $this->assertEmpty($injectionPolicy->getInjectableProperties($reflectionClass));
        }

        public function testGetParameterKey()
        {
            $injectionPolicy = new DefaultInjectionPolicy();

            $function = function(Foo $foo, $bar) {};
            $reflectionFunction = new \ReflectionFunction($function);
            $paramerters = $reflectionFunction->getParameters();

            $this->assertSame('Emonkak\Di\Tests\InjectionPolicy\DefaultInjectionPolicyTest\Foo', $injectionPolicy->getParameterKey($paramerters[0]));
            $this->assertSame('$bar', $injectionPolicy->getParameterKey($paramerters[1]));
        }

        public function testGetPropertyKey()
        {
            $injectionPolicy = new DefaultInjectionPolicy();

            $reflectionProperty = new \ReflectionProperty('Emonkak\Di\Tests\InjectionPolicy\DefaultInjectionPolicyTest\Foo', 'foo');

            $this->assertSame('$foo', $injectionPolicy->getPropertyKey($reflectionProperty));
        }

        public function testGetScope()
        {
            $injectionPolicy = new DefaultInjectionPolicy();

            $reflectionClass = new \ReflectionClass('stdClass');

            $this->assertInstanceOf('Emonkak\Di\Scope\PrototypeScope', $injectionPolicy->getScope($reflectionClass));
        }

        public function testIsInjectableClass()
        {
            $injectionPolicy = new DefaultInjectionPolicy();

            $fooClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\DefaultInjectionPolicyTest\Foo');
            $barClass = new \ReflectionClass('Emonkak\Di\Tests\InjectionPolicy\DefaultInjectionPolicyTest\Bar');

            $this->assertTrue($injectionPolicy->isInjectableClass($fooClass));
            $this->assertFalse($injectionPolicy->isInjectableClass($barClass));
        }
    }
}

namespace Emonkak\Di\Tests\InjectionPolicy\DefaultInjectionPolicyTest
{
    class Foo
    {
        public $foo;
    }

    class Bar
    {
        private function __construct() {}
    }
}
