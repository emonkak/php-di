<?php

namespace Emonkak\Di\Extras;

/**
 * Provides a loading of the service provider that is automatically generated.
 */
interface ServiceProviderLoaderInterface
{
    /**
     * Loads the class from cache of the service provider.
     *
     * @param string $className The class name of the service provider.
     */
    public function load($className);

    /**
     * Gets a value that indicates whether the specified URI can be loaded.
     *
     * @param string $className The class name of the service provider.
     * @return bool
     */
    public function canLoad($className);

    /**
     * Writes the source of the service provider.
     *
     * @param string $className The class name of the service provider.
     * @param string $source The source of the service provider.
     */
    public function write($className, $source);
}
