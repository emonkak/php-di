<?php

namespace Emonkak\Di\ServiceProvider;

use Composer\Autoload\ClassLoader;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * The service provider loader on local filesystem.
 */
class ServiceProviderLoaderOnFilesystem implements ServiceProviderLoaderInterface
{
    /**
     * @var string
     */
    private $dir;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ClassLoader
     */
    private $classLoader;

    /**
     * @param string      $dir The directory where to put the service provider
     * @param Filesystem  $filesystem
     * @param ClassLoader $classLoader
     */
    public function __construct($dir, Filesystem $filesystem = null, ClassLoader $classLoader = null)
    {
        $this->dir = $dir;
        $this->filesystem = $filesystem ?: new Filesystem();
        $this->classLoader = $classLoader ?: new ClassLoader();
        $this->classLoader->add('', $dir);
    }

    /**
     * {@inheritDoc}
     */
    public function load($className)
    {
        if (!$this->classLoader->loadClass($className)) {
            throw new FileNotFoundException(
                "Failed to load `$className` because file does not exist."
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function canLoad($className)
    {
        $path = $this->toFilePath($className);
        return $this->filesystem->exists($path);
    }

    /**
     * {@inheritDoc}
     */
    public function write($className, $source)
    {
        $path = $this->toFilePath($className);
        $this->filesystem->dumpfile($path, '<?php ' . $source);
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->filesystem->remove($this->dir);
    }

    /**
     * Converts the class name to the file path.
     *
     * @param string $className
     * @return string
     */
    private function toFilePath($className)
    {
        return $this->dir
              . DIRECTORY_SEPARATOR
              . str_replace('\\', DIRECTORY_SEPARATOR, $className)
              . '.php';
    }
}
