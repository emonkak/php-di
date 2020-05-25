<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests;

use Emonkak\Di\ContainerException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\ContainerException
 */
class ContainerExceptionTest extends TestCase
{
    public function testUninstantiableClass(): void
    {
        $class = new \ReflectionClass(\DateTimeInterface::class);
        $exception = ContainerException::uninstantiableClass($class);

        $this->assertSame('Class `DateTimeInterface` is not instantiable.', $exception->getMessage());
    }

    /**
     * @dataProvider providerUnresolvedParameter
     */
    public function testUnresolvedParameter(\ReflectionFunctionAbstract $function, int $parameterIndex, string $expectedFunctionName, string $expectedParameterName): void
    {
        $parameter = $function->getParameters()[$parameterIndex];
        $previous = $this->createMock(\Throwable::class);
        $exception = ContainerException::unresolvedParameter($parameter, $previous);

        $this->assertSame("Error while resolving the parameter `$expectedParameterName` from function `$expectedFunctionName`.", $exception->getMessage());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function providerUnresolvedParameter(): array
    {
        return [
            [new \ReflectionFunction(__NAMESPACE__ . '\\_testFunction'), 0, __NAMESPACE__ . '\\_testFunction()', '$foo'],
            [new \ReflectionFunction(__NAMESPACE__ . '\\_testFunction'), 1, __NAMESPACE__ . '\\_testFunction()', 'string $bar'],
            [new \ReflectionFunction(__NAMESPACE__ . '\\_testFunction'), 2, __NAMESPACE__ . '\\_testFunction()', 'array $baz'],
            [new \ReflectionFunction(__NAMESPACE__ . '\\_testFunction'), 3, __NAMESPACE__ . '\\_testFunction()', 'callable $qux'],
            [new \ReflectionFunction(__NAMESPACE__ . '\\_testFunction'), 4, __NAMESPACE__ . '\\_testFunction()', 'DateTime $quux'],
            [new \ReflectionMethod($this, '_testMethod'), 0, __CLASS__ . '::_testMethod()', '$foo'],
            [new \ReflectionMethod($this, '_testMethod'), 1, __CLASS__ . '::_testMethod()', 'string $bar'],
            [new \ReflectionMethod($this, '_testMethod'), 2, __CLASS__ . '::_testMethod()', 'array $baz'],
            [new \ReflectionMethod($this, '_testMethod'), 3, __CLASS__ . '::_testMethod()', 'callable $qux'],
            [new \ReflectionMethod($this, '_testMethod'), 4, __CLASS__ . '::_testMethod()', 'DateTime $quux'],
        ];
    }

    private function _testMethod($foo, string $bar, array $baz, callable $qux, \DateTime $quux): void
    {
    }
}

function _testFunction($foo, string $bar, array $baz, callable $qux, \DateTime $quux): void
{
}
