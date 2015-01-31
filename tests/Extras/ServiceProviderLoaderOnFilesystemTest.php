<?php

namespace Emonkak\Di\Tests\Extras;

use Emonkak\Di\Extras\ServiceProviderLoaderOnFilesystem;
use Symfony\Component\Filesystem\Filesystem;

class ServiceProviderLoaderOnFilesystemTest extends AbstractServiceProviderTest
{
    public function setUp()
    {
        $this->cacheDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid(__CLASS__);

        parent::setUp();
    }

    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove([$this->cacheDir]);
    }

    protected function prepareLoader()
    {
        return ServiceProviderLoaderOnFilesystem::create($this->cacheDir);
    }
}
