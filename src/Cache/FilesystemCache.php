<?php

namespace Emonkak\Di\Cache;

use Symfony\Component\Filesystem\Filesystem;

class FilesystemCache implements \ArrayAccess
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @param string          $directory
     * @param Filesystem|null $filesystem
     */
    public function __construct($directory, Filesystem $filesystem = null)
    {
        $this->directory = $directory;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $path = $this->toFilePath($offset);
        return unserialize(file_get_contents($path));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $path = $this->toFilePath($offset);
        return $this->filesystem->exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $path = $this->toFilePath($offset);
        $this->filesystem->dumpFile($path, serialize($value));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $path = $this->toFilePath($offset);
        $this->filesystem->remove($path);
    }

    /**
     * @param string $key
     * @return string
     */
    private function toFilePath($key)
    {
        $hash = md5($key);
        $parts = array_slice(str_split($hash, 2), 0, 2);
        return $this->directory .'/' . implode('/', $parts) . '/' . $hash;
    }
}
