<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy;

/**
 * @covers Emonkak\Di\AbstractContainer
 * @covers Emonkak\Di\Container
 */
class ContainerTest extends AbstractContrainerTest
{
    public function testCreate()
    {
        $this->assertInstanceOf(Container::class, Container::create());
    }

    protected function prepareContainer()
    {
        return Container::create(AnnotationInjectionPolicy::create());
    }
}
