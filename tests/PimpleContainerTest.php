<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\Extras\ServiceProviderGenerator;
use Emonkak\Di\Extras\ServiceProviderLoader;
use Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy;
use Emonkak\Di\PimpleContainer;
use Pimple\Container as Pimple;

class PimpleContainerTest extends AbstractContrainerTest
{
    public function testCreate()
    {
        $this->assertInstanceOf('Emonkak\Di\PimpleContainer', PimpleContainer::create());
    }

    protected function prepareContainer()
    {
        return PimpleContainer::create(AnnotationInjectionPolicy::create());
    }

    public function testAsArrayAccess()
    {
        $obj = new \stdClass();
        $f = function() {};

        $container = $this->prepareContainer();
        $container['foo'] = $obj;
        $container['bar'] = $f;

        $this->assertSame($obj, $container['foo']);
        $this->assertSame($f, $container['bar']);

        $this->assertTrue(isset($container['foo']));
        $this->assertTrue(isset($container['bar']));
        $this->assertFalse(isset($container['baz']));

        unset($container['foo']);
        unset($container['bar']);

        $this->assertFalse(isset($container['foo']));
        $this->assertFalse(isset($container['bar']));
    }
}
