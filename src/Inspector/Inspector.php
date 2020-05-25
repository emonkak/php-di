<?php

declare(strict_types=1);

namespace Emonkak\Di\Inspector;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\ContainerException;
use Emonkak\Di\NotFoundException;

/**
 * @implements InspectorInterface<array>
 */
class Inspector implements InspectorInterface
{
    private ParameterResolverInterface $parameterResolver;

    public static function createDefault(): self
    {
        return new Inspector(new ParameterResolver());
    }

    public function __construct(ParameterResolverInterface $parameterResolver)
    {
        $this->parameterResolver = $parameterResolver;
    }

    /**
     * {@inheritdoc}
     * @throws ContainerException
     */
    public function inspect(string $key, array $bindings)
    {
        if (isset($bindings[$key])) {
            $binding = $bindings[$key];
            $function = $binding->getFunction();
        } else {
            /** @var class-string $key */
            $class = new \ReflectionClass($key);
            $function = $class->getConstructor();

            if (!$class->isInstantiable()) {
                throw ContainerException::uninstantiableClass($class);
            }
        }
        $dependencies = $function !== null ? $this->inspectFunction($function, $bindings) : [];
        return [$key, $dependencies];
    }

    public function withCache(\ArrayAccess $cache): InspectorInterface
    {
        return new CachedInspector($this, $cache);
    }

    /**
     * @param array<string,BindingInterface> $bindings
     * @throws ContainerException
     */
    private function inspectFunction(\ReflectionFunctionAbstract $function, array $bindings): array
    {
        $dependencies = [];

        foreach ($function->getParameters() as $parameter) {
            try {
                $dependency = $this->inspectParameter($parameter, $bindings);
                if ($dependency !== null) {
                    $dependencies[] = $dependency;
                }
            } catch (NotFoundException $e) {
                throw ContainerException::unresolvedParameter($parameter, $e);
            }
        }

        return $dependencies;
    }

    /**
     * @throws NotFoundException
     */
    private function inspectParameter(\ReflectionParameter $parameter, array $bindings): ?array
    {
        $key = $this->parameterResolver->resolveKey($parameter);
        if (isset($bindings[$key])) {
            $binding = $bindings[$key];
            $function = $binding->getFunction();
            $dependencies = $function !== null ? $this->inspectFunction($function, $bindings) : [];
            return [$key, $dependencies];
        }

        $class = $this->parameterResolver->resolveClass($parameter);
        if ($class !== null && $class->name === $key && $class->isInstantiable()) {
            $function = $class->getConstructor();
            $dependencies = $function !== null ? $this->inspectFunction($function, $bindings) : [];
            return [$key, $dependencies];
        }

        if ($parameter->isOptional()) {
            if (!$parameter->isDefaultValueAvailable()) {
                return null;
            }
            $defaultValue = $parameter->getDefaultValue();
            return [$key, null, $defaultValue];
        }

        throw NotFoundException::noEntryKey($key);
    }
}
