<?php

namespace Emonkak\Di\Tests\Extras
{
    use Emonkak\Di\Container;
    use Emonkak\Di\Extras\ClassDiagramGenerator;
    use Emonkak\Di\Tests\Extras\ClassDiagramGeneratorTest\Qux;
    use Emonkak\Di\Tests\Extras\ClassDiagramGeneratorTest\Quux;
    use Emonkak\Di\Tests\Extras\ClassDiagramGeneratorTest\Corge;
    use Emonkak\Di\Tests\Extras\ClassDiagramGeneratorTest\Grault;
    use Symfony\Component\Process\Process;

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
                ->bind('Emonkak\Di\Tests\Extras\ClassDiagramGeneratorTest\Foo')
                ->withMethod('setQux', [
                    $this->container->factory('Emonkak\Di\Tests\Extras\ClassDiagramGeneratorTest\Qux', function() {
                        return new Qux();
                    })
                ])
                ->withMethod('setQuux', [
                    $this->container->factory('$quux', function() {
                        return new Quux();
                    })
                ])
                ->withMethod('setScalar', [
                    $this->container->set('$scalar', 123)
                ])
                ->withProperty('corge', $this->container->set('Emonkak\Di\Tests\Extras\ClassDiagramGeneratorTest\Corge', new Corge()))
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
                ['Emonkak\Di\Tests\Extras\ClassDiagramGeneratorTest\Foo'],
                ['stdClass'],
            ];
        }

        public function validateDiagram($diagram)
        {
            $process = new Process('dot -Tcanon');
            $process->setInput($diagram);
            $process->run();

            $this->assertTrue($process->isSuccessful());
            $this->assertNull($process->getErrorOutput());
        }
    }
}

namespace Emonkak\Di\Tests\Extras\ClassDiagramGeneratorTest
{
    class Foo
    {
        public $bar;
        public $baz;
        public $qux;
        public $quux;
        public $corge;
        public $grault;
        public $scalar;

        public function __construct(Bar $bar, Baz $baz)
        {
            $this->bar = $bar;
            $this->baz = $baz;
        }

        public function setQux(Qux $qux)
        {
            $this->qux = $qux;
        }

        public function setQuux(Quux $quux)
        {
            $this->quux = $quux;
        }

        public function setScalar($scalar)
        {
            $this->scalar = $scalar;
        }
    }

    class Bar
    {
        public $baz;

        public function __construct(Baz $baz)
        {
            $this->baz = $baz;
        }
    }

    class Baz
    {
    }

    class Qux
    {
    }

    class Quux
    {
    }

    class Corge
    {
    }

    class Grault
    {
    }
}
