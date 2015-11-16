<?php

namespace Emonkak\Di\Extras;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\DependencyInterface;
use Pimple\ServiceProviderInterface;

/**
 * Provides the instantiation of the service provider.
 */
class ServiceProviderFactory
{
    /**
     * @var ServiceProviderGenerator
     */
    private $generator;

    /**
     * @var ServiceProviderLoaderInterface
     */
    private $loader;

    /**
     * @param ServiceProviderGenerator       $generator
     * @param ServiceProviderLoaderInterface $loader
     * @param ContainerInterface             $container
     */
    public function __construct(ServiceProviderGenerator $generator, ServiceProviderLoaderInterface $loader, ContainerInterface $container)
    {
        $this->generator = $generator;
        $this->loader = $loader;
        $this->container = $container;
    }

    /**
     * Creates the instance of the service provider.
     *
     * @param string $target
     * @param string $serviceProviderClass
     * @return ServiceProviderInterface
     */
    public function create($target, $serviceProviderClass)
    {
        if (!class_exists($serviceProviderClass, false)) {
            if (!$this->loader->canLoad($serviceProviderClass)) {
                $dependency = $this->container->resolve($target);
                $source = $this->generator->generate($serviceProviderClass, $dependency);

                $this->loader->write($serviceProviderClass, $source);
            }

            $this->loader->load($serviceProviderClass);
        }

        return new $serviceProviderClass();
    }
}
