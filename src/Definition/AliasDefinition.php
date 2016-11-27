<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;
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
    protected function resolveDependency(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy)
    {
        return $resolver->resolve($this->target);
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy)
    {
        return PrototypeScope::getInstance();
    }
}
