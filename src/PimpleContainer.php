<?php

namespace Emonkak\Di;

use Emonkak\Di\Extras\ServiceProviderFactory;
use Emonkak\Di\Extras\ServiceProviderGenerator;
use Emonkak\Di\Extras\ServiceProviderGeneratorInterface;
use Emonkak\Di\Extras\ServiceProviderLoader;
use Emonkak\Di\Extras\ServiceProviderLoaderInterface;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
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
     * @param InjectionPolicyInterface          $injectionPolicy
     * @param \ArrayAccess                      $cache
     * @param Pimple                            $container
     * @param ServiceProviderGeneratorInterface $serviceProviderGenerator
     * @param ServiceProviderLoaderInterface    $serviceProviderLoader
     */
    public static function create(InjectionPolicyInterface $injectionPolicy = null, \ArrayAccess $cache = null, Pimple $container = null, ServiceProviderGeneratorInterface $serviceProviderGenerator = null, ServiceProviderLoaderInterface $serviceProviderLoader = null)
    {
        return new self(
            $injectionPolicy ?: new DefaultInjectionPolicy(),
            $cache ?: new \ArrayObject(),
            $container ?: new Pimple(),
            $serviceProviderGenerator ?: ServiceProviderGenerator::create(),
            $serviceProviderLoader ?: ServiceProviderLoader::create()
        );
    }

    /**
     * @param InjectionPolicyInterface          $injectionPolicy
     * @param \ArrayAccess                      $cache
     * @param Pimple                            $container
     * @param ServiceProviderGeneratorInterface $serviceProviderGenerator
     * @param ServiceProviderLoaderInterface    $serviceProviderLoader
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, \ArrayAccess $cache, Pimple $container, ServiceProviderGeneratorInterface $serviceProviderGenerator, ServiceProviderLoaderInterface $serviceProviderLoader)
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
    public function get($key)
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
    public function getValue($key)
    {
        return $this->container[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function hasValue($key)
    {
        return isset($this->container[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function setValue($key, $value)
    {
        if (method_exists($value, '__invoke')) {
            $this->container[$key] = $this->container->protect($value);
        } else {
            $this->container[$key] = $value;
        }
    }
}
