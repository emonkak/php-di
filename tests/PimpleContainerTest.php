<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\InjectionPolicy\AnnotationInjectionPolicy;
use Emonkak\Di\PimpleContainer;

/**
 * @covers Emonkak\Di\AbstractContainer
 * @covers Emonkak\Di\PimpleContainer
 */
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
}
