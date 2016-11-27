<?php

namespace Emonkak\Di\Extras;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\ResolverInterface;
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
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @param ServiceProviderGenerator       $generator
     * @param ServiceProviderLoaderInterface $loader
     * @param ResolverInterface              $resolver
     */
    public function __construct(ServiceProviderGenerator $generator, ServiceProviderLoaderInterface $loader, ResolverInterface $resolver)
    {
        $this->generator = $generator;
        $this->loader = $loader;
        $this->resolver = $resolver;
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
                $dependency = $this->resolver->resolve($target);
                $source = $this->generator->generate($serviceProviderClass, $dependency);

                $this->loader->write($serviceProviderClass, $source);
            }

            $this->loader->load($serviceProviderClass);
        }

        return new $serviceProviderClass();
    }
}
