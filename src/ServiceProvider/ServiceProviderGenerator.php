<?php

namespace Emonkak\Di\ServiceProvider;

use Emonkak\Di\Injection\MethodInjection;
use Emonkak\Di\Injection\PropertyInjection;
use Emonkak\Di\Value\InjectableValueInterface;
use Emonkak\Di\Value\InjectableValueVisitorInterface;
use Emonkak\Di\Value\ObjectValue;
use Emonkak\Di\Value\SingletonValue;
use Emonkak\Di\Value\UndefinedValue;

class ServiceProviderGenerator implements InjectableValueVisitorInterface
{
    private $definitions = [];

    /**
     * @{inheritDoc}
     */
    public function visitValue(InjectableValueInterface $value)
    {
        $key = spl_object_hash($value);
        $concrete = $value->materialize();
        $serialized = addslashes(serialize($concrete));

        $this->definitions[$key] = <<<EOL
        \$c['$key'] = unserialize('$serialized');
EOL;

        return $key;
    }

    /**
     * @{inheritDoc}
     */
    public function visitObjectValue(ObjectValue $value)
    {
        $methodCalls = [];
        $propertySetters = [];

        foreach ($value->getMethodInjections() as $methodInjection) {
            $methodCalls[] = $this->dumpMethodCall($methodInjection);
        }

        foreach ($value->getPropertyInjections() as $properyInjection) {
            $propertySetters[] = $this->dumpPropertySet($properyInjection);
        }

        $key = spl_object_hash($value);
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

        if (count($methodCalls) > 0 || count($propertySetters) > 0) {
            $className = $value->getClass()->getName();
            $factory = 'Closure::bind(' . $factory . ", \$this, '$className')";
        }

        if (!($value instanceof SingletonValue)) {
            $factory = '$c->factory(' . $factory . ')';
        }

        $this->definitions[$key] = <<<EOL
        \$c['$key'] = $factory;
EOL;

        return $key;
    }

    /**
     * @{inheritDoc}
     */
    public function visitUndefinedValue(UndefinedValue $value)
    {
        return $value->getTag();
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
     * @param ObjectValue $value
     * @return string
     */
    private function dumpNewInstance(ObjectValue $value)
    {
        $paramExprs = [];

        $constructorInjection = $value->getConstructorInjection();
        if ($constructorInjection) {
            foreach ($constructorInjection->getParameters() as $param) {
                $key = $param->getValue()->accept($this);
                $paramExprs[] = $this->dumpValueExpr($key);
            }
        }

        $className = $value->getClass()->getName();
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
            $key = $param->getValue()->accept($this);
            $paramExprs[] = $this->dumpValueExpr($key);
        }

        $methodName = $methodInjection->getMethod()->getName();
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
        $key = $properyInjection->getValue()->accept($this);
        $valueExpr = $this->dumpValueExpr($key);
        $properyName = $properyInjection->getProperty()->getName();

        return <<<EOL
            \$o->$properyName = $valueExpr;
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
