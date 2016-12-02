<?php

namespace Emonkak\Di\Scope;

use Emonkak\Di\Dependency\DependencyInterface;

class PrototypeScope implements ScopeInterface
{
    /**
     * Gets the singleton instance of this classs.
     *
     * @codeCoverageIgnore
     *
     * @return PrototypeScope
     */
    public static function getInstance()
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new PrototypeScope();
        }

        return $instance;
    }

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function get(DependencyInterface $dependency)
    {
        return $dependency;
    }
}
