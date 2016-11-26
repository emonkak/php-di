<?php

namespace Emonkak\Di\Tests\Scope;

use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Scope\PrototypeScope;

/**
 * @covers Emonkak\Di\Scope\PrototypeScope
 */
class PrototypeScopeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $scope = PrototypeScope::getInstance();

        $this->assertSame($scope, PrototypeScope::getInstance());
    }

    public function testGet()
    {
        $scope = PrototypeScope::getInstance();
        $factoryDependency = new FactoryDependency('foo', function() {}, []);
        $objectDependency = new ObjectDependency('foo', 'stdClass', [], [], []);
        $referenceDependency = new ReferenceDependency('foo');

        $this->assertSame($factoryDependency, $scope->get($factoryDependency));
        $this->assertSame($objectDependency, $scope->get($objectDependency));
        $this->assertSame($referenceDependency, $scope->get($referenceDependency));
    }
}
