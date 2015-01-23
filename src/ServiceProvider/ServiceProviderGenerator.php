<?php

namespace Emonkak\Di\ServiceProvider;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\DependencyVistorInterface;
use Emonkak\Di\Dependency\DependencyTraverserInterface;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ReferenceDependency;

class ServiceProviderGenerator implements DependencyVistorInterface, DependencyTraverserInterface, ServiceProviderGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function generate($className, DependencyInterface $dependency)
    {
        $definitions = [];

        foreach ($dependency->acceptTraverser($this) as $key => $expr) {
            if (!empty($expr)) {
                $definitions[$key] = $expr;
            }
        }

        $joinedServiceDefinitions = implode("\n", $definitions);

        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $namespacePart = "namespace $namespace;\n\n";
            $className = substr($className, $lastNsPos + 1);
        } else {
            $namespacePart = '';
        }

        $classPart = <<<EOL
class $className implements \Pimple\ServiceProviderInterface
{
    public function register(\Pimple\Container \$c)
    {
$joinedServiceDefinitions
    }
}
EOL;

        return $namespacePart . $classPart;
    }

    /**
     * {@inheritDoc}
     */
    public function map(DependencyInterface $dependency)
    {
        return $dependency->acceptVisitor($this);
    }

    /**
     * {@inheritDoc}
     */
    public function visitFactoryDependency(FactoryDependency $dependency)
    {
        $serialized = serialize($dependency->getFactory());
        $factoryKey = $dependency->getKey() . '@factory';

        $factoryDefinition = <<<EOL
        if (!isset(\$c['$factoryKey'])) {
            \$c['$factoryKey'] = function(\$c) {
                return unserialize('$serialized');
            };
        }
EOL;

        $arguments = $this->dumpArguments($dependency->getParameters());
        $joinedStatements = <<<EOL
            return \$c['$factoryKey']($arguments);
EOL;

        return $factoryDefinition . "\n" . $this->dumpServiceDefinition($dependency, $joinedStatements);
    }

    /**
     * {@inheritDoc}
     */
    public function visitObjectDependency(ObjectDependency $dependency)
    {
        $methodCalls = [];
        $propertySetters = [];

        foreach ($dependency->getMethodInjections() as $method => $parameters) {
            $methodCalls[] = $this->dumpMethodCall($method, $parameters);
        }

        foreach ($dependency->getPropertyInjections() as $propery => $value) {
            $propertySetters[] = $this->dumpPropertySetter($propery, $value);
        }

        $joinedStatements = implode("\n", array_merge(
            [$this->dumpConstructor($dependency)],
            $methodCalls,
            $propertySetters,
            [$this->dumpReturn()]
        ));

        return $this->dumpServiceDefinition($dependency, $joinedStatements);
    }

    /**
     * {@inheritDoc}
     */
    public function visitReferenceDependency(ReferenceDependency $dependency)
    {
        return null;
    }

    /**
     * @param ObjectDependency $dependency
     * @return string
     */
    private function dumpConstructor(ObjectDependency $dependency)
    {
        $className = $dependency->getClassName();
        $arguments = $this->dumpArguments($dependency->getConstructorParameters());

        return <<<EOL
                \$o = new \\$className($arguments);
EOL;
    }

    /**
     * @param string                $method
     * @param DependencyInterface[] $parameters
     * @return string
     */
    private function dumpMethodCall($method, array $parameters)
    {
        $arguments = $this->dumpArguments($parameters);

        return <<<EOL
                \$o->$method($arguments);
EOL;
    }

    /**
     * @param string              $propery
     * @param DependencyInterface $dependency
     * @return string
     */
    private function dumpPropertySetter($propery, DependencyInterface $dependency)
    {
        $expr = $this->dumpValueReference($dependency);

        return <<<EOL
                \$o->$propery = $expr;
EOL;
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

    /**
     * @param DependencyInterface $dependency
     * @return string
     */
    private function dumpArguments(array $dependencies)
    {
        $arguments = [];
        foreach ($dependencies as $dependency) {
            $arguments[] = $this->dumpValueReference($dependency);
        }
        return implode(', ', $arguments);
    }

    /**
     * @param DependencyInterface $dependency
     * @param string              $joinedStatements
     * @return string
     */
    private function dumpServiceDefinition(DependencyInterface $dependency, $joinedStatements)
    {
        $factory = <<<EOL
function(\$c) {
$joinedStatements
            }
EOL;

        if (!$dependency->isSingleton()) {
            $factory = '$c->factory(' . $factory . ')';
        }

        return <<<EOL
        if (!isset(\$c['{$dependency->getKey()}'])) {
            \$c['{$dependency->getKey()}'] = $factory;
        }
EOL;
    }

    /**
     * @param DependencyInterface $dependency
     * @return string
     */
    private function dumpValueReference(DependencyInterface $dependency)
    {
        return "\$c['{$dependency->getKey()}']";
    }
}
