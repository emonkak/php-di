<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy;

class ContainerTest extends AbstractContrainerTest
{
    public function testCreate()
    {
        $this->assertInstanceOf('Emonkak\Di\Container', Container::create());
    }

    protected function prepareContainer()
    {
        return Container::create(AnnotationInjectionPolicy::create());
    }
}
