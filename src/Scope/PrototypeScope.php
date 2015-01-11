<?php

namespace Emonkak\Di\Scope;

use Emonkak\Di\Value\InjectableValueInterface;

class PrototypeScope implements ScopeInterface
{
    /**
     * Gets the singleton instance of this classs.
     *
     * @return PrototypeScope
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
        return $value;
    }
}
