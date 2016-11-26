<?php

namespace Emonkak\Di\Tests\Extras;

use Emonkak\Di\Extras\ServiceProviderLoader;

/**
 * @covers Emonkak\Di\Extras\ServiceProviderLoader
 */
class ServiceProviderLoaderTest extends AbstractServiceProviderLoaderTest
{
    public function testCreate()
    {
        $this->assertInstanceOf('Emonkak\Di\Extras\ServiceProviderLoader', ServiceProviderLoader::create());
    }

    protected function prepareLoader()
    {
        return ServiceProviderLoader::create();
    }
}
