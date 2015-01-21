<?php

namespace Emonkak\Di\Dependency;

interface DependencyVistorInterface
{
    /**
     * @param FactoryDependency $dependency
     * @return mixed
     */
    public function visitFactoryDependency(FactoryDependency $dependency);

    /**
     * @param ObjectDependency $dependency
     * @return mixed
     */
    public function visitObjectDependency(ObjectDependency $dependency);

    /**
     * @param ReferenceDependency $dependency
     * @return mixed
     */
    public function visitReferenceDependency(ReferenceDependency $dependency);
}
