<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests\Inspector;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Inspector\Inspector;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

/**
 * @covers \Emonkak\Di\Inspector\Inspector
 */
class InspectorTest extends TestCase
{
    public function testWithCache(): void
    {
        $cache = new \ArrayObject();
        $cachcedInspector = Inspector::createDefault()->withCache($cache);
        $this->assertSame($cache, $cachcedInspector->getCache());
    }

    public function testInspect(): void
    {
        $bindings = [
            BarInterface::class => $this->createMock(BindingInterface::class),
        ];

        $inspector = Inspector::createDefault();

        $this->assertSame([
            TestService::class,
            [
                [Foo::class, []],
                [BarInterface::class, []],
                [BazInterface::class, null, null],
            ],
        ], $inspector->inspect(TestService::class, $bindings));
        $this->assertSame([Foo::class, []], $inspector->inspect(Foo::class, $bindings));
        $this->assertSame([BarInterface::class, []], $inspector->inspect(BarInterface::class, $bindings));
    }

    /**
     * @dataProvider providerInspectThrowsException
     */
    public function testInspectThrowsException(string $key): void
    {
        $this->expectException(ContainerExceptionInterface::class);

        $inspector = Inspector::createDefault();

        $inspector->inspect($key, []);
    }

    public function providerInspectThrowsException(): array
    {
        return [
            [TestService::class],
            [BarInterface::class],
        ];
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
