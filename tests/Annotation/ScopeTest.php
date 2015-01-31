<?php

namespace Emonkak\Di\Tests\Annotation;

use Emonkak\Di\Annotation\Scope;

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
            [Scope::PROTOTYPE, 'Emonkak\Di\Scope\PrototypeScope'],
            [Scope::SINGLETON, 'Emonkak\Di\Scope\SingletonScope'],
        ];
    }
}
