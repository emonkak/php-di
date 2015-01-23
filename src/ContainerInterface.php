<?php

namespace Emonkak\Di;

use Doctrine\Common\Cache\Cache;
use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

interface ContainerInterface
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
     * @param string $target
     * @return AliasDefinition
     */
    public function alias($key, $target);

    /**
     * @param string $target
     * @return BindingDefinition
     */
    public function bind($target);

    /**
     * @param string   $key
     * @param callable $target
     * @return FactoryDefinition
     */
    public function factory($key, callable $target);

    /**
     * @param string $key
     * @param mixed  $value
     * @return ReferenceDependency
     */
    public function set($key, $value);

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setInstance($key, $value);

    /**
     * @param string $key
     * @return DependencyInterface
     */
    public function get($key);

    /**
     * @param string $key
     * @return mixed
     */
    public function getInstance($key);

    /**
     * @param string $key
     * @return boolean
     */
    public function has($key);

    /**
     * @param string $key
     * @return boolean
     */
    public function hasInstance($key);
}
