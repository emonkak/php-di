<?php

namespace Emonkak\Di\ServiceProvider;

/**
 * The service provider loader on memory.
 */
class ServiceProviderLoaderOnMemory implements ServiceProviderLoaderInterface
{
    /**
     * @var string[]
     */
    private $sources = [];

    /**
     * {@inheritDoc}
     */
    public function load($className)
    {
        if (!isset($this->sources[$className])) {
            throw new \RuntimeException(
                "Failed to load `$className` because the cache does not exist."
            );
        }

        eval($this->sources[$className]);
    }

    /**
     * {@inheritDoc}
     */
    public function canLoad($className)
    {
        return isset($this->sources[$className]);
    }

    /**
     * {@inheritDoc}
     */
    public function write($className, $source)
    {
        $this->sources[$className] = $source;
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->sources = [];
    }
}
