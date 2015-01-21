<?php

namespace Emonkak\Di\Dependency;

interface DependencyInterface
{
    /**
     * @param DependencyVistorInterface $visitor
     * @return mixed
     */
    public function accept(DependencyVistorInterface $visitor);

    /**
     * @param \ArrayAccess
     * @return mixed
     */
    public function inject(\ArrayAccess $valueBag);
}
