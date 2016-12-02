<?php

namespace Emonkak\Di\Tests\Annotation;

use Emonkak\Di\Annotation\Scope;
use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Scope\SingletonScope;

/**
 * @covers Emonkak\Di\Annotation\Scope
 */
class ScopeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideGetScope
     */
    public function testGetScope($value, $exceptedInstance)
    {
        $scope = new Scope();
        $scope->value = $value;

        $this->assertInstanceOf($exceptedInstance, $scope->getScope());
    }

    public function testGetScopeReturnsNull()
    {
        $scope = new Scope();

        $this->assertNull($scope->getScope());
    }

    public function provideGetScope()
    {
        return [
            [Scope::PROTOTYPE, PrototypeScope::class],
            [Scope::SINGLETON, SingletonScope::class],
        ];
    }
}
