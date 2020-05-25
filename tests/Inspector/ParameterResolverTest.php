<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests\Inspector;

use Emonkak\Di\Inspector\ParameterResolver;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\Inspector\ParameterResolver
 */
class ParameterResolverTest extends TestCase
{
    public function testResolve(): void
    {
        $method = new \ReflectionMethod($this, '_testMethod');
        $parameters = $method->getParameters();

        $parameterResolver = new ParameterResolver();

        $this->assertSame('$foo', $parameterResolver->resolveKey($parameters[0]));
        $this->assertNull($parameterResolver->resolveClass($parameters[0]));
        $this->assertSame(\DateTime::class, $parameterResolver->resolveKey($parameters[1]));
        $this->assertEquals(new \ReflectionClass(\DateTime::class), $parameterResolver->resolveClass($parameters[1]));
    }

    private function _testMethod($foo, \DateTime $bar)
    {
    }
}
