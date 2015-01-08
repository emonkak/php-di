<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

class ObjectValue implements InjectableValueInterface
{
    private $class;
    private $methodInjections;
    private $properyInjections;

    /**
     * @param \ReflectionClass    $class
     * @param MethodInjection[]   $methodInjections
     * @param PropertyInjection[] $properyInjections
     */
    public function __construct(\ReflectionClass $class, array $methodInjections, array $properyInjections)
    {
        $this->class = $class;
        $this->methodInjections = $methodInjections;
        $this->properyInjections = $properyInjections;
    }

    /**
     * @param Injector $injector
     * @return mixed
     */
    public function materialize(Injector $injector)
    {
        // TODO:
    }
}
