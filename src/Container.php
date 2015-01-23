<?php

namespace Emonkak\Di;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

class Container extends AbstractContainer
{
    /**
     * @var \ArrayAcccess
     */
    private $instancePool;

    /**
     * @return Container
     */
    public static function create()
    {
        return new self(new DefaultInjectionPolicy(), new ArrayCache(), new \ArrayObject());
    }

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param Cache                    $cache
     * @param \ArrayAccess             $instancePool
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, Cache $cache, \ArrayAccess $instancePool)
    {
        parent::__construct($injectionPolicy, $cache);

        $this->instancePool = $instancePool;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setInstance($key, $value)
    {
        $this->instancePool[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getInstance($key)
    {
        if (isset($this->instancePool[$key])) {
            return $this->instancePool[$key];
        }

        return $this->get($key)->inject($this);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function hasInstance($key)
    {
        return isset($this->instancePool[$key]);
    }
}
