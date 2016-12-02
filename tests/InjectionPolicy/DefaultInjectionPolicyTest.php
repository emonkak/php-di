<?php

use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Tests\Fixtures\Foo;

/**
 * @covers Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy
 */
class DefaultInjectionPolicyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInjectableMethods()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $reflectionClass = new \ReflectionClass(\stdClass::class);

        $this->assertEmpty($injectionPolicy->getInjectableMethods($reflectionClass));
    }

    public function testGetInjectableProperties()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $reflectionClass = new \ReflectionClass(\stdClass::class);

        $this->assertEmpty($injectionPolicy->getInjectableProperties($reflectionClass));
    }

    public function testGetParameterKey()
    {
        $injectionPolicy = new DefaultInjectionPolicy();

        $function = function(Foo $foo, $bar) {};
        $reflectionFunction = new \ReflectionFunction($function);
        $paramerters = $reflectionFunction->getParameters();

        $this->assertSame(Foo::class, $injectionPolicy->getParameterKey($paramerters[0]));
        $this->assertSame('$bar', $injectionPolicy->getParameterKey($paramerters[1]));
    }

    public function testGetPropertyKey()
    {
        $injectionPolicy = new DefaultInjectionPolicy();

        $obj = (object) ['foo' => 123];
        $property = (new ReflectionObject($obj))->getProperty('foo');

        $this->assertSame('$foo', $injectionPolicy->getPropertyKey($property));
    }

    public function testGetScope()
    {
        $injectionPolicy = new DefaultInjectionPolicy();

        $reflectionClass = new \ReflectionClass(\stdClass::class);

        $this->assertInstanceOf(PrototypeScope::class, $injectionPolicy->getScope($reflectionClass));
    }

    public function testIsInjectableClass()
    {
        $injectionPolicy = new DefaultInjectionPolicy();

        $this->assertTrue($injectionPolicy->isInjectableClass(new \ReflectionClass(InjectableService::class)));
        $this->assertFalse($injectionPolicy->isInjectableClass(new \ReflectionClass(NotInjectableService::class)));
    }
}

class InjectableService
{
}

class NotInjectableService
{
    private function __construct()
    {
    }
}
