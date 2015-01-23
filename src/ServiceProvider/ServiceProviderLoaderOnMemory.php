<?php

namespace Emonkak\Di\ServiceProvider;

/**
 * The service provider loader on memory.
 */
class ServiceProviderLoaderOnMemory implements ServiceProviderLoaderInterface
{
    /**
     * @var array of the srouce string
     */
    private $sources = [];

    /**
     * {@inheritDoc}
     */
    public function load($className)
    {
        if (!isset($this->sources[$className])) {
            throw new \InvalidArgumentException(
                "Failed to load `$className` because file does not exist."
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
}
