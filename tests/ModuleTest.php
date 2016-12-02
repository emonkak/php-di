<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Module;

/**
 * @covers Emonkak\Di\Module
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testMerge()
    {
        $module1 = new Module();
        $module2 = new Module();
        $this->assertSame($module1, $module1->merge($module2));
    }

    public function testAlias()
    {
        $module = new Module();
        $definition = $module->alias('foo', 'bar');
        $this->assertInstanceOf(AliasDefinition::class, $definition);
    }

    public function testBind()
    {
        $module = new Module();
        $definition = $module->bind(\stdClass::class);
        $this->assertInstanceOf(BindingDefinition::class, $definition);
    }

    public function testFactory()
    {
        $module = new Module();
        $factory = function() {
        };
        $definition = $module->factory(\stdClass::class, $factory);
        $this->assertInstanceOf(FactoryDefinition::class, $definition);
    }

    public function testSet()
    {
        $module = new Module();
        $definition = $module->set(\stdClass::class, new \stdClass());
        $this->assertInstanceOf(ReferenceDependency::class, $definition);
    }
}
