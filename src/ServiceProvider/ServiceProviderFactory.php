<?php

namespace Emonkak\Di\ServiceProvider;

use Emonkak\Di\Container;
use Pimple\ServiceProviderInterface;

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
    public function __construct(Container $container, ServiceProviderLoaderInterface $loader)
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
     * @return ServiceProviderInterface
     */
    public function createInstance(array $serviceClasses, $serviceProviderClass, $serviceProviderNamespace = '')
    {
        $className = ltrim($serviceProviderNamespace . '\\' . $serviceProviderClass, '\\');

        if (!class_exists($className, false)) {
            if (!$this->loader->canLoad($className)) {
                $generator = new ServiceProviderGenerator();

                foreach ($serviceClasses as $serviceClass) {
                    $value = $this->container->get($serviceClass);
                    $value->accept($generator);
                }

                $source = $generator->generate($serviceProviderClass, $serviceProviderNamespace);

                $this->loader->write($className, $source);
            }

            $this->loader->load($className);
        }

        return new $serviceProviderClass();
    }
}
