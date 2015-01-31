<?php

namespace Emonkak\Di\Tests\Extras;

abstract class AbstractServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    public function setUp()
    {
        $this->loader = $this->prepareLoader();
    }

    public function testLoad()
    {
        do {
            $className = 'Class_' . md5(mt_rand());
        } while (class_exists($className));

        $this->assertFalse($this->loader->canLoad($className));

        $this->loader->write($className, "class $className {}");

        $this->assertTrue($this->loader->canLoad($className));

        $this->loader->load($className);

        $this->assertTrue(class_exists($className));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testLoadThrowsRuntimeException()
    {
        $this->loader->load('not_definied');
    }

    abstract protected function prepareLoader();
}
