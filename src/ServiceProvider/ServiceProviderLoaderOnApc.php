<?php

namespace Emonkak\Di\ServiceProvider;

/**
 * The service provider loader on APC.
 */
class ServiceProviderLoaderOnApc implements ServiceProviderLoaderInterface
{
    /**
     * {@inheritDoc}
     */
    public function load($className)
    {
        $source = apc_fetch($className, $result);

        if (!$result) {
            throw new \RuntimeException(
                "Failed to load `$className` because the cache does not exist."
            );
        }

        eval($source);
    }

    /**
     * {@inheritDoc}
     */
    public function canLoad($className)
    {
        return apc_exists($className);
    }

    /**
     * {@inheritDoc}
     */
    public function write($className, $source)
    {
        $result = apc_store($className, $source);

        if (!$result) {
            throw new \RuntimeException('Failed to store the source cache.');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        apc_clear_cache();
    }
}
