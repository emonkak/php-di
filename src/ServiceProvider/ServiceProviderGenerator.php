<?php

namespace Emonkak\Di\ServiceProvider;

use Emonkak\Di\Container;
use Emonkak\Di\Injection\MethodInjection;
use Emonkak\Di\Injection\PropertyInjection;
use Emonkak\Di\Value\InjectableValueInterface;
use Emonkak\Di\Value\InjectableValueVisitorInterface;
use Emonkak\Di\Value\ObjectValueInterface;
use Emonkak\Di\Value\ProviderValue;
use Emonkak\Di\Value\SingletonValue;

class ServiceProviderGenerator implements InjectableValueVisitorInterface
{
    private $container;

    private $definitions = [];

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function visitValue(InjectableValueInterface $value)
    {
        return $this->container->getKey($value);
    }

    /**
     * {@inheritDoc}
     */
    public function visitObjectValue(ObjectValueInterface $value)
    {
        $methodCalls = [];
        $propertySetters = [];

        foreach ($value->getMethodInjections() as $methodInjection) {
            $methodCalls[] = $this->dumpMethodCall($methodInjection);
        }

        foreach ($value->getPropertyInjections() as $properyInjection) {
            $propertySetters[] = $this->dumpPropertySet($properyInjection);
        }

        $procedures = implode("\n", array_merge(
            [$this->dumpNewInstance($value)],
            $methodCalls,
            $propertySetters,
            [$this->dumpReturn()]
        ));

            $factory = <<<EOL
function(\$c) {
$procedures
        }
EOL;

        if (!($value instanceof SingletonValue)) {
            $factory = '$c->factory(' . $factory . ')';
        }

        $key = $this->container->getKey($value);
        $this->definitions[$key] = <<<EOL
        \$c['$key'] = $factory;
EOL;

        return $key;
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
     * @param ObjectValueInterface $value
     * @return string
     */
    private function dumpNewInstance(ObjectValueInterface $value)
    {
        $paramExprs = [];

        $constructorInjection = $value->getConstructorInjection();
        if ($constructorInjection) {
            foreach ($constructorInjection->getParameters() as $param) {
                $paramKey = $param->accept($this);
                $paramExprs[] = $this->dumpValueExpr($paramKey);
            }
        }

        $className = $value->getClassName();
        $joinedParamExprs = implode(', ', $paramExprs);

        return <<<EOL
            \$o = new $className($joinedParamExprs);
EOL;
    }

    /**
     * @param MethodInjection $methodInjection
     * @return string
     */
    private function dumpMethodCall(MethodInjection $methodInjection)
    {
        $paramExprs = [];

        foreach ($methodInjection->getParameters() as $param) {
            $paramKey = $param->accept($this);
            $paramExprs[] = $this->dumpValueExpr($paramKey);
        }

        $methodName = $methodInjection->getMethodName();
        $joinedParamExprs = implode(', ', $paramExprs);

        return <<<EOL
            \$o->$methodName($joinedParamExprs);
EOL;
    }

    /**
     * @param PropertyInjection $properyInjection
     * @return string
     */
    private function dumpPropertySet(PropertyInjection $properyInjection)
    {
        $properyKey = $properyInjection->getValue()->accept($this);
        $properyExpr = $this->dumpValueExpr($properyKey);
        $properyName = $properyInjection->getPropertyName();

        return <<<EOL
            \$o->$properyName = $properyExpr;
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
