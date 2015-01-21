<?php

namespace Emonkak\Di\Dependency;

class SharedDependency extends FactoryDependency
{
    /**
     * @var boolean
     */
    private $isCached;

    /**
     * @var mixed
     */
    private $cache;

    /**
     * @param FactoryDependency $dependency
     * @return SharedDependency
     */
    public static function from(FactoryDependency $dependency)
    {
        return new self(
            $dependency->factory,
            $dependency->parameters
        );
    }

    /**
     * @return string[]
     */
    public function __sleep()
    {
        return ['factory', 'parameters'];
    }

    /**
     * {@inheritDoc}
     */
    public function inject(\ArrayAccess $valueBag)
    {
        if (!$this->isCached) {
            $this->isCached = true;
            $this->cache = parent::inject($valueBag);
        }
        return $this->cache;
    }
}
