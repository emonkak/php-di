<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Scope\PrototypeScope;

class AliasDefinition extends AbstractDefinition
{
    /**
     * @var string
     */
    private $target;

    /**
     * @param string $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveDependency(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy)
    {
        return $container->resolve($this->target);
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy)
    {
        return PrototypeScope::getInstance();
    }
}
