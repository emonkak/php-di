<?php

namespace Emonkak\Di\Tests\Exception;

use Emonkak\Di\Exception\KeyNotFoundException;
use Emonkak\Di\Tests\Exception\Stubs\Foo;

class KeyNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testFromProperty()
    {
        $key = 'foo';
        $property = new \ReflectionProperty(Foo::class, 'foo');
        $prev = new KeyNotFoundException();
        $exception = KeyNotFoundException::fromProperty('foo', $property, $prev);

        $this->assertInstanceOf(KeyNotFoundException::class, $exception);
        $this->assertStringMatchesFormat('Error while resolving "%s" from "%s::$%s" in %s:%d', $exception->getMessage());
    }

    public function testFromParameter()
    {
        $key = 'foo';
        $parameters = (new \ReflectionClass(Foo::class))->getConstructor()->getParameters();
        $prev = new KeyNotFoundException();
        $exception = KeyNotFoundException::fromParameter('foo', $parameters[0], $prev);

        $this->assertInstanceOf(KeyNotFoundException::class, $exception);
        $this->assertStringMatchesFormat('Error while resolving "%s" from "%s::%s" in %s:%d', $exception->getMessage());
    }
}
