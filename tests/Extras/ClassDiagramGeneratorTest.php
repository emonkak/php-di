<?php

namespace Emonkak\Di\Tests\Extras;

use Emonkak\Di\Container;
use Emonkak\Di\Extras\ClassDiagramGenerator;
use Emonkak\Di\Tests\Extras\Stubs\Corge;
use Emonkak\Di\Tests\Extras\Stubs\Grault;
use Emonkak\Di\Tests\Extras\Stubs\Quux;
use Emonkak\Di\Tests\Extras\Stubs\Qux;
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
            ->bind('Emonkak\Di\Tests\Extras\Stubs\Foo')
            ->withMethod('setQux', [
                $this->container->factory('Emonkak\Di\Tests\Extras\Stubs\Qux', function() {
                    return new Qux();
                })
            ])
            ->withMethod('setQuux', [
                $this->container->factory('$quux', function() {
                    return new Quux();
                })
            ])
            ->withMethod('setAny', [
                $this->container->set('$any', 'any')
            ])
            ->withProperty('corge', $this->container->set('Emonkak\Di\Tests\Extras\Stubs\Corge', new Corge()))
            ->withProperty('grault', $this->container->set('$grault', new Grault()));
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
            ['Emonkak\Di\Tests\Extras\Stubs\Foo'],
            ['stdClass'],
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
