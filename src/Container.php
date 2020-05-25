<?php

declare(strict_types=1);

namespace Emonkak\Di;

use Emonkak\Di\Inspector\Inspector;
use Emonkak\Di\Inspector\InspectorInterface;
use Emonkak\Di\Instantiator\Instantiator;
use Emonkak\Di\Instantiator\InstantiatorInterface;
use Psr\Container\ContainerInterface;

/**
 * @template TDependency
 */
class Container extends Module implements ContainerInterface
{
    /**
     * @var InspectorInterface<TDependency>
     */
    private InspectorInterface $inspector;

    /**
     * @var InstantiatorInterface<TDependency>
     */
    private InstantiatorInterface $instantiator;

    /**
     * @return self<array>
     */
    public static function createDefault(): self
    {
        return new Container(Inspector::createDefault(), new Instantiator());
    }

    /**
     * @param InspectorInterface<TDependency> $inspector
     * @param InstantiatorInterface<TDependency> $instantiator
     */
    public function __construct(InspectorInterface $inspector, InstantiatorInterface $instantiator)
    {
        parent::__construct();

        $this->inspector = $inspector;
        $this->instantiator = $instantiator;
    }

    public function get($key)
    {
        $dependency = $this->inspector->inspect($key, $this->bindings);
        return $this->instantiator->instantiate($dependency, $this->bindings);
    }

    public function has($key)
    {
        return isset($this->bindings[$key]) || class_exists($key, true);
    }
}
