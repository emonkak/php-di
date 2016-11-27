<?php

namespace Emonkak\Di\Extras;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Dependency\ValueDependency;

class ClassDiagramGenerator implements DependencyVisitorInterface
{
    /**
     * @param DependencyInterface $dependency
     * @return string
     */
    public function generate(DependencyInterface $dependency)
    {
        $clusters = ['children' => []];
        $nodes = [];
        $edges = [];
        $traversed = [];

        foreach ($dependency as $key => $child) {
            if (!isset($traversed[$key])) {
                $item = $child->accept($this);

                if ($item['namespace'] !== '') {
                    $fragments = [];
                    $paths = explode('\\', $item['namespace']);
                    $target = &$clusters;

                    foreach ($paths as $path) {
                        $fragments[] = $path;
                        if (!isset($target['children'][$path])) {
                            $target['children'][$path] = [
                                'namespace' => implode('\\', $fragments),
                                'items' => [],
                                'children' => [],
                            ];
                        }
                        $target = &$target['children'][$path];
                    }

                    $target['items'][] = $item;
                } else {
                    $nodes[] = $this->showGraph($item);
                }

                $edges = array_merge($edges, $item['edges']);
                $traversed[$key] = true;
            }
        }

        foreach ($clusters['children'] as $cluster) {
            $nodes[] = $this->showSubgraph($cluster);
        }

        $joinedNodes = implode("\n", $nodes);
        $joinedEdges = implode("\n", $edges);

        return <<<EOL
digraph "{$this->escape($dependency->getKey())}" {
    fontname = "Helvetica"
    fontsize = 10
    node [
        fontname = "Helvetica"
        fontsize = 10
        shape = "record"
    ]
    edge [
        arrowhead = "empty"
    ]
$joinedNodes
$joinedEdges
}
EOL;
    }

    /**
     * {@inheritDoc}
     */
    public function visitFactoryDependency(FactoryDependency $dependency)
    {
        $key = $dependency->getKey();
        if (class_exists($key) || interface_exists($key)) {
            $class = new \ReflectionClass($key);
            return $this->getItemByClass($dependency, $class, $class->getShortName() . '@factory', $key);
        } else {
            return [
                'key' => $key,
                'namespace' => '',
                'label' => sprintf('{%s@factory}', $this->escape($key)),
                'edges' => $this->showEdges($dependency),
            ];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function visitObjectDependency(ObjectDependency $dependency)
    {
        $class = new \ReflectionClass($dependency->getClassName());
        return $this->getItemByClass($dependency, $class, $class->getShortName());
    }

    /**
     * {@inheritDoc}
     */
    public function visitReferenceDependency(ReferenceDependency $dependency)
    {
        $key = $dependency->getKey();
        if (class_exists($key) || interface_exists($key)) {
            $class = new \ReflectionClass($key);
            return $this->getItemByClass($dependency, $class, $class->getShortName() . '@reference');
        } else {
            return [
                'key' => $key,
                'namespace' => '',
                'label' => sprintf('{%s@reference}', $this->escape($key)),
                'edges' => []
            ];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function visitValueDependency(ValueDependency $dependency)
    {
        $key = $dependency->getKey();
        return [
            'key' => $key,
            'namespace' => '',
            'label' => sprintf('{%s}', $this->escape(var_export($dependency->getValue(), true))),
            'edges' => []
        ];
    }

    /**
     * @param DependencyInterface $dependency
     * @param \ReflectionClass $class
     * @param string $subject
     * @return array
     */
    private function getItemByClass(DependencyInterface $dependency, \ReflectionClass $class, $subject)
    {
        $properties = $this->extractProperties($class);
        $methods = $this->extractMethods($class);;

        $label = sprintf(
            '{%s|%s\l|%s\l}',
            $this->escape($subject),
            implode('\l', $properties),
            implode('\l', $methods)
        );

        return [
            'key' => $dependency->getKey(),
            'namespace' => $class->getNamespaceName(),
            'label' => $label,
            'edges' => $this->showEdges($dependency),
        ];
    }

    /**
     * @param \ReflectionClass $class
     * @return string[]
     */
    private function extractProperties(\ReflectionClass $class)
    {
        $properties = [];

        foreach ($class->getProperties() as $propery) {
            if ($propery->isPublic() && !$propery->isStatic()) {
                $properties[] = $this->formatProperty($propery);
            }
        }

        return $properties;
    }

    /**
     * @param \ReflectionClass $class
     * @return string[]
     */
    private function extractMethods(\ReflectionClass $class)
    {
        $methods = [];

        foreach ($class->getMethods() as $method) {
            if ($method->isPublic() && !$method->isStatic()) {
                $methods[] = $this->formatMethod($method);
            }
        }

        return $methods;
    }

    /**
     * @param \ReflectionProperty $property
     * @return string
     */
    private function formatProperty(\ReflectionProperty $property)
    {
        return sprintf('+ %s', $property->name);
    }

    /**
     * @param \ReflectionMethod $method
     * @return string
     */
    private function formatMethod(\ReflectionMethod $method)
    {
        $parameters = [];

        foreach ($method->getParameters() as $parameter) {
            $parameterClass = $parameter->getClass();

            if ($parameterClass) {
                $parameters[] = "$parameter->name: {$parameterClass->getShortName()}";
            } else {
                $parameters[] = $parameter->name;
            }
        }

        return sprintf('+ %s(%s)', $method->name, implode(', ', $parameters));
    }

    /**
     * @param DependencyInterface $dependency
     * @return string[]
     */
    private function showEdges(DependencyInterface $dependency)
    {
        $edges = [];
        foreach ($dependency->getDependencies() as $dep) {
            $edges[] = sprintf('    "%s" -> "%s"', $this->escape($dependency->getKey()), $this->escape($dep->getKey()));
        }
        return $edges;
    }

    /**
     * @param array $item
     * @return string
     */
    private function showGraph(array $item)
    {
        return <<<EOL
    "{$this->escape($item['key'])}" [
        style = "filled"
        label = "{$item['label']}"
    ]
EOL;
    }

    /**
     * @param array epackage
     * @return string
     */
    private function showSubgraph(array $cluster)
    {
        $nodes = [];

        foreach ($cluster['children'] as $child) {
            $nodes[] = $this->showSubgraph($child);
        }

        foreach ($cluster['items'] as $item) {
            $nodes[] = $this->showGraph($item);
        }

        $joinedNodes = implode("\n", $nodes);

        if (!empty($cluster['items'])) {
            return <<<EOL
    subgraph "cluster_{$this->escape($cluster['namespace'])}" {
        style = "rounded"
        label = "namespace {$this->escape($cluster['namespace'])}"
$joinedNodes
    }
EOL;
        } else {
            return <<<EOL
    subgraph "cluster_{$this->escape($cluster['namespace'])}" {
        style = "invisible"
$joinedNodes
    }
EOL;
        }
    }

    /**
     * @param string $string
     * @return string
     */
    private function escape($string)
    {
        return str_replace('\\', '\\\\', $string);
    }
}
