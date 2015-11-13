<?php

namespace Emonkak\Di;

use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Interop\Container\ContainerInterface as InteropContainerInterface;

interface ContainerInterface extends InteropContainerInterface
{
    /**
     * @return InjectionFinder
     */
    public function getInjectionFinder();

    /**
     * @return InjectionPolicyInterface
     */
    public function getInjectionPolicy();

    /**
     * @param ContainerConfiguratorInterface $configurator
     */
    public function configure(ContainerConfiguratorInterface $configurator);

    /**
     * @param string $key
     * @return mixed
     */
    public function getValue($key);

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setValue($key, $value);

    /**
     * @param string $key
     * @return boolean
     */
    public function hasValue($key);

    /**
     * @param string $key
     * @return DependencyInterface
     */
    public function resolve($key);
}
