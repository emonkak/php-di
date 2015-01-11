<?php

namespace Emonkak\Di\Scope;

use Emonkak\Di\Value\CachedValue;
use Emonkak\Di\Value\InjectableValueInterface;
use Emonkak\Di\Value\InjectableValueVisitorInterface;
use Emonkak\Di\Value\ObjectValueInterface;
use Emonkak\Di\Value\SingletonValue;

class SingletonScope implements ScopeInterface, InjectableValueVisitorInterface
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
    public function get(InjectableValueInterface $value)
    {
        return $value->accept($this);
    }

    /**
     * {@inheritDoc}
     */
    public function visitValue(InjectableValueInterface $value)
    {
        if (!($value instanceof CachedValue)) {
            return new CachedValue($value);
        }
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function visitObjectValue(ObjectValueInterface $value)
    {
        if (!($value instanceof SingletonValue)) {
            return new SingletonValue($value);
        }
        return $value;
    }
}
