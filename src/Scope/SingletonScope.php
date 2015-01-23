<?php

namespace Emonkak\Di\Scope;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\DependencyVistorInterface;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Dependency\SharedDependency;
use Emonkak\Di\Dependency\SingletonDependency;

class SingletonScope implements ScopeInterface, DependencyVistorInterface
{
    /**
     * Gets the singleton instance of this classs.
     *
     * @return SingletonScope
     */
    public static function getInstance()
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    private function __construct() {}

    /**
     * {@inheritDoc}
     */
    public function get(DependencyInterface $dependency)
    {
        return $dependency->acceptVisitor($this);
    }

    /**
     * {@inheritDoc}
     */
    public function visitFactoryDependency(FactoryDependency $dependency)
    {
        return SharedDependency::from($dependency);
    }

    /**
     * @param ObjectDependency $dependency
     * @return mixed
     */
    public function visitObjectDependency(ObjectDependency $dependency)
    {
        return SingletonDependency::from($dependency);
    }

    /**
     * @param ReferenceDependency $dependency
     * @return mixed
     */
    public function visitReferenceDependency(ReferenceDependency $dependency)
    {
        return $dependency;
    }
}
