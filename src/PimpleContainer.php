<?php

namespace Emonkak\Di;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ServiceProvider\ServiceProviderFactory;
use Emonkak\Di\ServiceProvider\ServiceProviderGenerator;
use Emonkak\Di\ServiceProvider\ServiceProviderGeneratorInterface;
use Emonkak\Di\ServiceProvider\ServiceProviderLoaderInterface;
use Emonkak\Di\ServiceProvider\ServiceProviderLoaderOnMemory;
use Pimple\Container as Pimple;

class PimpleContainer extends AbstractContainer
{
    /**
     * @var Pimple
     */
    private $container;

    /**
     * @var ServiceProviderFactory
     */
    private $serviceProviderFactory;

    /**
     * @return PimpleContainer
     */
    public static function create()
    {
        return new self(
            new DefaultInjectionPolicy(),
            new ArrayCache(),
            new Pimple(),
            new ServiceProviderGenerator(),
            new ServiceProviderLoaderOnMemory()
        );
    }

    /**
     * @param InjectionPolicyInterface          $injectionPolicy
     * @param Cache                             $cache
     * @param Pimple                            $container
     * @param ServiceProviderGeneratorInterface $serviceProviderGenerator
     * @param ServiceProviderLoaderInterface    $serviceProviderLoader
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, Cache $cache, Pimple $container, ServiceProviderGeneratorInterface $serviceProviderGenerator, ServiceProviderLoaderInterface $serviceProviderLoader)
    {
        parent::__construct($injectionPolicy, $cache);

        $this->container = $container;
        $this->serviceProviderFactory = new ServiceProviderFactory(
            $serviceProviderGenerator,
            $serviceProviderLoader,
            $this
        );
    }

    /**
     * {@inheritDoc}
     */
    public function setInstance($key, $value)
    {
        if (method_exists($value, '__invoke')) {
            $this->container[$key] = $this->container->protect($value);
        } else {
            $this->container[$key] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getInstance($key)
    {
        if (!isset($this->container[$key])) {
            $serviceProvider = $this->serviceProviderFactory->create($key, $key . 'Provider');
            $this->container->register($serviceProvider);
        }

        return $this->container[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function hasInstance($key)
    {
        return isset($this->container[$key]);
    }
}
