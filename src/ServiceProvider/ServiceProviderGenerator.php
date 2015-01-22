<?php

namespace Emonkak\Di\ServiceProvider;

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\DependencyVistorInterface;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Dependency\SingletonDependency;
use Emonkak\Di\Injection\MethodInjection;
use Emonkak\Di\Injection\PropertyInjection;

class ServiceProviderGenerator implements DependencyVistorInterface
{
    /**
     * @var string[]
     */
    private $definitions = [];

    /**
     * {@inheritDoc}
     */
    public function visitFactoryDependency(FactoryDependency $dependency)
    {
        return $dependency->getKey();
    }

    /**
     * {@inheritDoc}
     */
    public function visitObjectDependency(ObjectDependency $dependency)
    {
        $key = $dependency->getKey();
        if (isset($this->definitions[$key])) {
            return $key;
        }

        $methodCalls = [];
        $propertySetters = [];

        foreach ($dependency->getMethodInjections() as $method => $parameters) {
            $methodCalls[] = $this->dumpMethodCall($method, $parameters);
        }

        foreach ($dependency->getPropertyInjections() as $propery => $value) {
            $propertySetters[] = $this->dumpPropertySetter($propery, $value);
        }

        $procedures = implode("\n", array_merge(
            [$this->dumpNewInstance($dependency)],
            $methodCalls,
            $propertySetters,
            [$this->dumpReturn()]
        ));

            $factory = <<<EOL
function(\$c) {
$procedures
        }
EOL;

        if (!($dependency instanceof SingletonDependency)) {
            $factory = '$c->factory(' . $factory . ')';
        }

        $this->definitions[$key] = <<<EOL
        \$c['$key'] = $factory;
EOL;

        return $key;
    }

    /**
     * {@inheritDoc}
     */
    public function visitReferenceDependency(ReferenceDependency $dependency)
    {
        return $dependency->getKey();
    }

    /**
     * @param string $className
     * @param string $namespace
     * @return string
     */
    public function generate($className, $namespace = '')
    {
        $joinedServiceDefinitions = implode("\n", $this->definitions);

        $namespaceSource = $namespace !== '' ? "namespace $namespace;\n\n" : '';
        $classSource = <<<EOL
class $className implements \Pimple\ServiceProviderInterface
{
    public function register(\Pimple\Container \$c)
    {
$joinedServiceDefinitions
    }
}
EOL;

        return $namespaceSource . $classSource;
    }

    /**
     * @param ObjectDependency $dependency
     * @return string
     */
    private function dumpNewInstance(ObjectDependency $dependency)
    {
        $paramExprs = [];

        $constructorParameters = $dependency->getConstructorParameters();
        foreach ($constructorParameters as $parameter) {
            $paramKey = $parameter->accept($this);
            $paramExprs[] = $this->dumpValueExpr($paramKey);
        }

        $className = $dependency->getClassName();
        $joinedParamExprs = implode(', ', $paramExprs);

        return <<<EOL
            \$o = new $className($joinedParamExprs);
EOL;
    }

    /**
     * @param string                $method
     * @param DependencyInterface[] $parameters
     * @return string
     */
    private function dumpMethodCall($method, array $parameters)
    {
        $paramExprs = [];

        foreach ($parameters as $parameter) {
            $paramKey = $parameter->accept($this);
            $paramExprs[] = $this->dumpValueExpr($paramKey);
        }

        $joinedParamExprs = implode(', ', $paramExprs);

        return <<<EOL
            \$o->$method($joinedParamExprs);
EOL;
    }

    /**
     * @param string              $propery
     * @param DependencyInterface $dependency
     * @return string
     */
    private function dumpPropertySetter($propery, DependencyInterface $dependency)
    {
        $key = $dependency->accept($this);
        $properyExpr = $this->dumpValueExpr($key);

        return <<<EOL
            \$o->$propery = $properyExpr;
EOL;
    }

    /**
     * @param string $key
     * @return string
     */
    private function dumpValueExpr($key)
    {
        return "\$c['$key']";
    }

    /**
     * @return string
     */
    private function dumpReturn()
    {
        return <<<EOL
            return \$o;
EOL;
    }
}
