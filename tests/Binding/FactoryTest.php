<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests\Binding;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Binding\Factory;
use Emonkak\Di\Instantiator\InstantiatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\Binding\Factory
 */
class FactoryTest extends TestCase
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
        $instance = (object) ['id' => 4];

        $spy = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();
        $spy
            ->expects($this->once())
            ->method('__invoke')
            ->withConsecutive(
                [$this->identicalTo($dependencies[0])],
                [$this->identicalTo($dependencies[1])],
                [$this->identicalTo($dependencies[2])],
            )
            ->willReturn($instance);

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

        $this->assertSame($instance, (new Factory($spy))->resolve($dependencies, $bindings, $instantiator));
    }

    public function testGetFunction(): void
    {
        $functionObject = new class() {
            public function __invoke()
            {
            }
        };
        $closure = function() {
        };

        $this->assertEquals(new \ReflectionMethod(\DateTime::class, 'format'), (new Factory([new \DateTime('1970-01-01'), 'format']))->getFunction());
        $this->assertEquals(new \ReflectionMethod($functionObject, '__invoke'), (new Factory($functionObject))->getFunction());
        $this->assertEquals(new \ReflectionFunction($closure), (new Factory($closure))->getFunction());
    }
}
