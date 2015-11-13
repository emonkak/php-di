<?php

namespace Emonkak\Di;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

class Container extends AbstractContainer
{
    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ArrayAccess             $cache
     * @param \ArrayAccess             $pool
     * @return Container
     * @return self
     */
    public static function create(InjectionPolicyInterface $injectionPolicy = null, \ArrayAccess $cache = null, \ArrayAccess $pool = null)
    {
        return new self(
            $injectionPolicy ?: new DefaultInjectionPolicy(),
            $cache ?: new \ArrayObject(),
            $pool ?: new \ArrayObject()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if (isset($this->pool[$key])) {
            return $this->pool[$key];
        }

        return $this->resolve($key)->materializeBy($this, $this->pool);
    }
}
