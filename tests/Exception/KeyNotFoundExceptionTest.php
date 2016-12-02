<?php

namespace Emonkak\Di\Tests\Exception;

use Emonkak\Di\Exception\KeyNotFoundException;

class KeyNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testUnresolvedProperty()
    {
        $property = new \ReflectionProperty(KeyNotFoundExceptionTestService::class, 'foo');
        $prev = new KeyNotFoundException();
        $exception = KeyNotFoundException::unresolvedProperty($property, $prev);

        $this->assertInstanceOf(KeyNotFoundException::class, $exception);
        $this->assertStringMatchesFormat('Error while resolving the property "%s::$%s"', $exception->getMessage());
    }

    public function testUnresolvedParameter()
    {
        $parameters = (new \ReflectionClass(KeyNotFoundExceptionTestService::class))->getConstructor()->getParameters();
        $prev = new KeyNotFoundException();
        $exception = KeyNotFoundException::unresolvedParameter($parameters[0], $prev);

        $this->assertInstanceOf(KeyNotFoundException::class, $exception);
        $this->assertStringMatchesFormat('Error while resolving the parameter "%s" from function "%s"', $exception->getMessage());
    }
}

class KeyNotFoundExceptionTestService
{
    public $foo;

    public function __construct($foo)
    {
    }
}
