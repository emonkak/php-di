<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests\Instantiator;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Instantiator\Instantiator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\Instantiator\Instantiator
 */
class InstantiatorTest extends TestCase
{
    public function testInstantiate(): void
    {
        $bindings = [
            BarInterface::class => $this->createMock(BindingInterface::class),
        ];

        $instantiator = new Instantiator();

        $bindings[BarInterface::class]
            ->expects($this->atLeastOnce())
            ->method('resolve')
            ->with($this->identicalTo([]), $this->identicalTo($bindings), $this->identicalTo($instantiator))
            ->willReturn(new Bar());

        $expectedInstance = new TestService(new Foo(), new Bar(), null);
        $dependency = [
            TestService::class,
            [
                [Foo::class, []],
                [BarInterface::class, []],
                [BazInterface::class, null, null],
            ],
        ];

        $this->assertEquals($expectedInstance, $instantiator->instantiate($dependency, $bindings));
        $this->assertEquals(new Foo(), $instantiator->instantiate([Foo::class, []], $bindings));
        $this->assertEquals(new Bar(), $instantiator->instantiate([BarInterface::class, []], $bindings));
        $this->assertNull($instantiator->instantiate([BazInterface::class, null, null], $bindings));
    }
}

class TestService
{
    private Foo $foo;

    private BarInterface $bar;

    private ?BazInterface $baz;

    private array $qux;

    public function __construct(Foo $foo, BarInterface $bar, BazInterface $baz = null, QuxInterface ...$qux)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
    }
}

class Foo
{
}

interface BarInterface
{
}

class Bar implements BarInterface
{
}

interface BazInterface
{
}

class Baz implements BazInterface
{
}

interface QuxInterface
{
}

class Qux implements QuxInterface
{
}
