<?php

namespace Emonkak\Di\Scope;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Dependency\SingletonDependency;
use Emonkak\Di\Dependency\ValueDependency;

class SingletonScope implements ScopeInterface, DependencyVisitorInterface
{
    /**
     * Gets the singleton instance of this classs.
     *
     * @codeCoverageIgnore
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
        return $dependency->accept($this);
    }

    /**
     * {@inheritDoc}
     */
    public function visitFactoryDependency(FactoryDependency $dependency)
    {
        return $dependency->asSingleton();
    }

    /**
     * {@inheritDoc}
     */
    public function visitObjectDependency(ObjectDependency $dependency)
    {
        return $dependency->asSingleton();
    }

    /**
     * {@inheritDoc}
     */
    public function visitReferenceDependency(ReferenceDependency $dependency)
    {
        return $dependency;
    }

    /**
     * {@inheritDoc}
     */
    public function visitValueDependency(ValueDependency $dependency)
    {
        return $dependency;
    }
}
