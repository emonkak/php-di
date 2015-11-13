<?php

namespace Emonkak\Di;

use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

class Container extends AbstractContainer
{
    /**
     * @var \ArrayAcccess
     */
    private $pool;

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ArrayAccess             $cache
     * @param \ArrayAccess             $pool
     * @return Container
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
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ArrayAccess             $cache
     * @param \ArrayAccess             $pool
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, \ArrayAccess $cache, \ArrayAccess $pool)
    {
        parent::__construct($injectionPolicy, $cache);

        $this->pool = $pool;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (isset($this->pool[$key])) {
            return $this->pool[$key];
        }

        return $this->resolve($key)->materializeBy($this);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getValue($key)
    {
        return $this->pool[$key];
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setValue($key, $value)
    {
        $this->pool[$key] = $value;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function hasValue($key)
    {
        return isset($this->pool[$key]);
    }
}
