<?php

namespace Emonkak\Di\Tests\Extras;

use Emonkak\Di\Container;
use Emonkak\Di\Extras\ClassDiagramGenerator;
use Emonkak\Di\Tests\Fixtures\Bar;
use Emonkak\Di\Tests\Fixtures\Baz;
use Emonkak\Di\Tests\Fixtures\Foo;
use Symfony\Component\Process\Process;

/**
 * @covers Emonkak\Di\Extras\ClassDiagramGenerator
 */
class ClassDiagramGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        system('which dot > /dev/null 2>&1', $result);
        if ($result !== 0) {
            $this->markTestSkipped('`dot` command is required.');
        }

        $this->container = Container::create();
        $this->container
            ->bind(ClassDiagramGeneratorTestService::class)
            ->withMethod('setBar', [
                $this->container->factory(Bar::class, function() {
                    return new Bar();
                })
            ])
            ->withMethod('setQux', [
                $this->container->set('$qux', 'qux')
            ])
            ->withMethod('setQuux', [
                $this->container->factory('$quux', function() {
                    return 'quux';
                })
            ])
            ->withProperty('baz', $this->container->set(Baz::class, new Baz()));
    }

    /**
     * @dataProvider provideGenerate
     */
    public function testGenerate($key)
    {
        $dependency = $this->container->resolve($key);

        $generator = new ClassDiagramGenerator();
        $diagram = $generator->generate($dependency);

        $this->validateDiagram($diagram);
    }

    public function provideGenerate()
    {
        return [
            [ClassDiagramGeneratorTestService::class],
            [\stdClass::class],
        ];
    }

    public function validateDiagram($diagram)
    {
        $process = new Process('dot -Tcanon');
        $process->setInput($diagram);
        $process->run();

        $this->assertTrue($process->isSuccessful());
        $this->assertEquals('', $process->getErrorOutput());
    }
}

class ClassDiagramGeneratorTestService
{
    public $foo;

    public $bar;

    public $baz;

    public $qux;

    public $quux;

    public function __construct(Foo $foo, $optional = null)
    {
        $this->foo = $bar;
    }

    public function setBar(Bar $bar)
    {
        $this->bar = $bar;
    }

    public function setQux($qux)
    {
        $this->qux = $qux;
    }
}
