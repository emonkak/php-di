<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests\Binding;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Binding\Implementation;
use Emonkak\Di\Instantiator\InstantiatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\Binding\Implementation
 */
class ImplementationTest extends TestCase
{
    public function testResolve(): void
    {
        $bindings = [
            'key' => $this->createMock(BindingInterface::class),
        ];
        $dependencies = [
            (object) ['id' => 1],
            (object) ['id' => 2],
            (object) ['id' => 3],
        ];
        $instance = new TestImplementation(...$dependencies);

        $instantiator = $this->createMock(InstantiatorInterface::class);
        $instantiator
            ->expects($this->exactly(3))
            ->method('instantiate')
            ->withConsecutive(
                [$this->identicalTo($dependencies[0]), $this->identicalTo($bindings)],
                [$this->identicalTo($dependencies[1]), $this->identicalTo($bindings)],
                [$this->identicalTo($dependencies[2]), $this->identicalTo($bindings)],
            )
            ->will($this->returnArgument(0));

        $this->assertEquals($instance, (new Implementation(TestImplementation::class))->resolve($dependencies, $bindings, $instantiator));
    }

    public function testGetFunction(): void
    {
        $binding = new Implementation(TestImplementation::class);

        $this->assertEquals((new \ReflectionClass(TestImplementation::class))->getConstructor(), $binding->getFunction());
    }
}

class TestImplementation
{
    public function __construct($foo, $bar, $baz)
    {
    }
}
