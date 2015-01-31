<?php

namespace Emonkak\Di\Extras;

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
    private $directory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param string $directory  The directory where to put the service provider
     * @return ServiceProviderLoaderOnFilesystem
     */
    public static function create($directory)
    {
        return new self($directory, new Filesystem());
    }

    /**
     * @param string $path
     */
    private static function includeFile($path)
    {
        include $path;
    }

    /**
     * @param string     $directory  The directory where to put the service provider
     * @param Filesystem $filesystem
     */
    public function __construct($directory, Filesystem $filesystem)
    {
        $this->directory = $directory;
        $this->filesystem = $filesystem;
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
     * Converts the class name to the file path.
     *
     * @param string $className
     * @return string
     */
    private function toFilePath($className)
    {
        return $this->directory
              . str_replace('\\', DIRECTORY_SEPARATOR, $className)
              . '.php';
    }
}
