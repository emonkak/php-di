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
}
