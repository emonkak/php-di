<?php

namespace Emonkak\Di\Tests\Scope;

use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Dependency\SingletonDependency;
use Emonkak\Di\Dependency\SingletonFactoryDependency;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\Scope\SingletonScope;

/**
 * @covers Emonkak\Di\Scope\SingletonScope
 */
class SingletonScopeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $scope = SingletonScope::getInstance();

        $this->assertSame($scope, SingletonScope::getInstance());
    }

    public function testGet()
    {
        $scope = SingletonScope::getInstance();

        $factoryDependency = new FactoryDependency('foo', function() {}, []);
        $this->assertInstanceOf(SingletonFactoryDependency::class, $scope->get($factoryDependency));

        $objectDependency = new ObjectDependency('foo', \stdClass::class, [], [], []);
        $this->assertInstanceOf(SingletonDependency::class, $scope->get($objectDependency));

        $referenceDependency = new ReferenceDependency('foo');
        $this->assertSame($referenceDependency, $scope->get($referenceDependency));

        $valueDependency = new ValueDependency(123);
        $this->assertSame($valueDependency, $scope->get($valueDependency));
    }
}
