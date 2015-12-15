<?php

namespace Emonkak\Di\Dependency;

interface DependencyVisitorInterface
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

    /**
     * @param ValueDependency $dependency
     * @return mixed
     */
    public function visitValueDependency(ValueDependency $dependency);
}
