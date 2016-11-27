<?php

namespace Emonkak\Di\Tests\Exception;

use Emonkak\Di\Exception\KeyNotFoundException;

class KeyNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testUnresolvedProperty()
    {
        $key = 'foo';
        $property = new \ReflectionProperty(KeyNotFoundExceptionTestService::class, 'foo');
        $prev = new KeyNotFoundException();
        $exception = KeyNotFoundException::unresolvedProperty('foo', $property, $prev);

        $this->assertInstanceOf(KeyNotFoundException::class, $exception);
        $this->assertStringMatchesFormat('Error while resolving "%s" from "%s::$%s" in %s:%d', $exception->getMessage());
    }

    public function testUnresolvedParameter()
    {
        $key = 'foo';
        $parameters = (new \ReflectionClass(KeyNotFoundExceptionTestService::class))->getConstructor()->getParameters();
        $prev = new KeyNotFoundException();
        $exception = KeyNotFoundException::unresolvedParameter('foo', $parameters[0], $prev);

        $this->assertInstanceOf(KeyNotFoundException::class, $exception);
        $this->assertStringMatchesFormat('Error while resolving "%s" from "%s::%s" in %s:%d', $exception->getMessage());
    }
}

class KeyNotFoundExceptionTestService
{
    public $foo;

    public function __construct($foo)
    {
    }
}
