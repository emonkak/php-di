<?php

namespace Emonkak\Di;

use Interop\Container\ContainerInterface as InteropContainerInterface;

interface ContainerInterface extends InteropContainerInterface
{
    /**
     * @return InjectionFinder
     */
    public function getInjectionFinder();

    /**
     * @param string $key
     * @return DependencyInterface
     */
    public function resolve($key);
}
