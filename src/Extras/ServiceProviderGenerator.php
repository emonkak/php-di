<?php

namespace Emonkak\Di\Extras;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ReferenceDependency;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinterAbstract;
use PhpParser\PrettyPrinter\Standard as PrettyPrinterStandard;

class ServiceProviderGenerator implements DependencyVisitorInterface, ServiceProviderGeneratorInterface
{
    /**
     * @var PrettyPrinterAbstract
     */
    private $prettyPrinter;

    /**
     * @return ServiceProviderGenerator
     */
    public static function create()
    {
        return new self(new PrettyPrinterStandard());
    }

    /**
     * @param PrettyPrinterAbstract $prettyPrinter
     */
    public function __construct(PrettyPrinterAbstract $prettyPrinter)
    {
        $this->prettyPrinter = $prettyPrinter;
    }

    /**
     * {@inheritDoc}
     */
    public function generate($className, DependencyInterface $dependency)
    {
        $ast = $this->generateAst($className, $dependency);
        return $this->prettyPrinter->prettyPrint([$ast]);
    }

    /**
     * @param string              $className
     * @param DependencyInterface $dependency
     * @return Node
     */
    public function generateAst($className, DependencyInterface $dependency)
    {
        $factory = new BuilderFactory();

        $registerBuilder = $factory
            ->method('register')
            ->addParam($factory->param('c')
            ->setTypeHint(new Name\FullyQualified('Pimple\Container')));
        $traversed = [];

        $dependency->traverse(function($dependency, $key) use (&$traversed, $registerBuilder) {
            if (!isset($traversed[$key])) {
                $stmts = $dependency->accept($this);
                foreach ($stmts as $stmt) {
                    $registerBuilder->addStmt($stmt);
                }
                $traversed[$key] = true;
            }
        });

        list ($namespace, $className) = $this->splitToNamesapceAndClass($className);

        $classBuilder = $factory
            ->class($className)
            ->implement(new Name\FullyQualified('Pimple\ServiceProviderInterface'))
            ->addStmt($registerBuilder);

        if ($namespace !== '') {
            $classBuilder = $factory->namespace($namespace)->addStmt($classBuilder);
        }

        return $classBuilder->getNode();
    }

    /**
     * {@inheritDoc}
     */
    public function visitFactoryDependency(FactoryDependency $dependency)
    {
        $variable = new Expr\Variable('f');

        $factoryStmts = [
            new Expr\Assign(
                $variable,
                new Expr\FuncCall(new Name('unserialize'), [
                    new Scalar\String(serialize($dependency->getFactory()))
                ])
            ),
            new Stmt\Return_(
                new Expr\FuncCall($variable, array_map([$this, 'createContainerAccessor'], $dependency->getParameters()))
            )
        ];

        return [$this->createServiceFactoryDefinition($dependency, $factoryStmts)];
    }

    /**
     * {@inheritDoc}
     */
    public function visitObjectDependency(ObjectDependency $dependency)
    {
        $variable = new Expr\Variable('o');

        $factoryStmts = [new Expr\Assign(
            $variable,
            new Expr\New_(
                new Name\FullyQualified($dependency->getClassName()),
                array_map([$this, 'createContainerAccessor'], $dependency->getConstructorParameters())
            )
        )];

        foreach ($dependency->getMethodInjections() as $method => $parameters) {
            $factoryStmts[] = new Expr\MethodCall(
                $variable,
                $method,
                array_map([$this, 'createContainerAccessor'], $parameters)
            );
        }

        foreach ($dependency->getPropertyInjections() as $propery => $value) {
            $factoryStmts[] = new Expr\Assign(
                new Expr\PropertyFetch($variable, $propery),
                $this->createContainerAccessor($value)
            );
        }

        $factoryStmts[] = new Stmt\Return_($variable);

        return [$this->createServiceFactoryDefinition($dependency, $factoryStmts)];
    }

    /**
     * {@inheritDoc}
     */
    public function visitReferenceDependency(ReferenceDependency $dependency)
    {
        return [];
    }

    /**
     * @param DependencyInterface $dependency
     * @return Expr
     */
    private function createContainerAccessor(DependencyInterface $dependency)
    {
        return new Expr\ArrayDimFetch(
            new Expr\Variable('c'),
            new Scalar\String($dependency->getKey())
        );
    }

    /**
     * @param DependencyInterface $dependency
     * @param Node[]              $stmts
     * @return Expr
     */
    private function createServiceFactory(DependencyInterface $dependency, array $stmts)
    {
        $factory = new Expr\Closure([
            'params' => [new Param('c')],
            'stmts' => $stmts,
        ]);

        if (!$dependency->isSingleton()) {
            $factory = new Expr\MethodCall(
                new Expr\Variable('c'),
                'factory',
                [$factory]
            );
        }

        return $factory;
    }

    /**
     * @param DependencyInterface $dependency
     * @param Node[]              $stmts
     * @return Node
     */
    private function createServiceFactoryDefinition(DependencyInterface $dependency, array $stmts)
    {
        $definition = new Expr\Assign(
            $this->createContainerAccessor($dependency),
            $this->createServiceFactory($dependency, $stmts)
        );

        if ($dependency->isSingleton()) {
            $definition = new Stmt\If_(
                new Expr\BooleanNot(
                    new Expr\Isset_([$this->createContainerAccessor($dependency)])
                ),
                ['stmts' => [$definition]]
            );
        }

        return $definition;
    }

    /**
     * @param string $className
     * @return array (namespace, className)
     */
    private function splitToNamesapceAndClass($className)
    {
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            return [$namespace, $className];
        } else {
            return ['', $className];
        }
    }
}
