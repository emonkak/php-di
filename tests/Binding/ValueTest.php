<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests\Binding;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Binding\Value;
use Emonkak\Di\Instantiator\InstantiatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\Binding\Value
 */
class ValueTest extends TestCase
{
    public function testResolve(): void
    {
        $value = 123;
        $binding = new Value($value);
        $bindings = [
            'key' => $this->createMock(BindingInterface::class),
        ];
        $dependencies = [];
        $instantiator = $this->createMock(InstantiatorInterface::class);

        $this->assertEquals($value, $binding->resolve($dependencies, $bindings, $instantiator));
    }

    public function testGetFunction(): void
    {
        $binding = new Value(123);

        $this->assertNull($binding->getFunction());
    }
}
