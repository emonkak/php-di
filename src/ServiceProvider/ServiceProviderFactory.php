<?php

namespace Emonkak\Di\ServiceProvider;

use Emonkak\Di\Container;

/**
 * Provides the instantiation of the service provider.
 */
class ServiceProviderFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var ServiceProviderLoaderInterface
     */
    private $loader;

    /**
     * @param Container                      $container
     * @param ServiceProviderLoaderInterface $loader
     */
    public function __construct(
        Container $container,
        ServiceProviderLoaderInterface $loader)
    {
        $this->container = $container;
        $this->loader = $loader;
    }

    /**
     * Creates the instance of the service provider.
     *
     * @param string[] $serviceClasses
     * @param string   $serviceProviderClass
     * @param string   $serviceProviderNamespace
     * @return mixed
     */
    public function createInstance(array $serviceClasses, $serviceProviderClass, $serviceProviderNamespace = '')
    {
        if (!class_exists($serviceProviderClass, false)) {
            if (!$this->loader->canLoad($serviceProviderClass)) {
                $generator = new ServiceProviderGenerator($this->container);

                foreach ($serviceClasses as $serviceClass) {
                    $value = $this->container->get($serviceClass);
                    $value->accept($generator);
                }

                $className = ltrim($serviceProviderNamespace . '\\' . $serviceProviderClass, '\\');
                $source = $generator->generate($serviceProviderClass, $serviceProviderNamespace);

                $this->loader->write($className, $source);
            }

            $this->loader->load($serviceProviderClass);
        }

        return new $serviceProviderClass();
    }
}
