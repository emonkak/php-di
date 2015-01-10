<?php

namespace Emonkak\Di\ServiceProvider;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
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
     * @param string $path
     */
    private static function includeFile($path)
    {
        include $path;
    }

    /**
     * @param string      $dir The directory where to put the service provider
     * @param Filesystem  $filesystem
     */
    public function __construct($dir, Filesystem $filesystem = null)
    {
        $this->dir = $dir;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    /**
     * {@inheritDoc}
     */
    public function load($className)
    {
        $path = $this->toFilePath($className);

        if (!$this->filesystem->exists($path)) {
            throw new FileNotFoundException(
                sprintf('Failed to load "%s" because the file does not exist.', $path)
            );
        }

        self::includeFile($path);
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
