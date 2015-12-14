<?php

namespace Emonkak\Di\Tests\Scope;

use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Scope\SingletonScope;

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
        $objectDependency = new ObjectDependency('foo', 'stdClass', [], [], []);
        $referenceDependency = new ReferenceDependency('foo');
        $valueDependency = new ValueDependency(123);

        $this->assertInstanceOf('Emonkak\Di\Dependency\FlyweightFactoryDependency', $scope->get($factoryDependency));
        $this->assertInstanceOf('Emonkak\Di\Dependency\SingletonDependency', $scope->get($objectDependency));
        $this->assertSame($referenceDependency, $scope->get($referenceDependency));
        $this->assertSame($valueDependency, $scope->get($valueDependency));
    }
}
