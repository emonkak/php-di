<?php

namespace Emonkak\Di;

use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Dependency\ValueDependency;

class Module
{
    /**
     * @var array array(string => DefinitionInterface)
     */
    protected $definitions = [];

    /**
     * @var array array(string => mixed)
     */
    protected $values = [];

    /**
     * @param Module $module
     * @return $this
     */
    public function merge(Module $module)
    {
        $this->definitions = $module->definitions + $this->definitions;
        $this->values = $module->values + $this->values;
        return $this;
    }

    /**
     * @param string $key
     * @param string $target
     * @return AliasDefinition
     */
    public function alias($key, $target)
    {
        return $this->definitions[$key] = new AliasDefinition($target);
    }

    /**
     * @param string $target
     * @return BindingDefinition
     */
    public function bind($target)
    {
        return $this->definitions[$target] = new BindingDefinition($target);
    }

    /**
     * @param string   $key
     * @param callable $target
     * @return FactoryDefinition
     */
    public function factory($key, callable $target)
    {
        return $this->definitions[$key] = new FactoryDefinition($key, $target);
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @return ReferenceDependency
     */
    public function set($key, $value)
    {
        $this->values[$key] = $value;
        return $this->definitions[$key] = new ReferenceDependency($key);
    }
}
