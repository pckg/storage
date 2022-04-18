<?php

namespace Pckg\Storage;

use League\Flysystem\FilesystemAdapter;
use Pckg\Storage\Exception\InvalidAdapterOptions;

class Storage
{
    protected FilesystemAdapter $adapter;

    public function __construct(FilesystemAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public static function createInstance(array $config = [])
    {
        $adapter = $config['adapter'];
        $options = $config['options'] ?? [];
        if (!is_array($options)) {
            if (!is_only_callable($options)) {
                throw new InvalidAdapterOptions();
            }
            $options = $options();
        }

        return new Storage(new $adapter(...$options));
    }

    public function setAdapter(FilesystemAdapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter(): FilesystemAdapter
    {
        return $this->adapter;
    }

    public function file(string $file): File
    {
        return (new File($file))->setAdapter($this->adapter);
    }

    public function dir(string $dir): Directory
    {
        return (new Directory($dir))->setAdapter($this->adapter);
    }

    public function read(string $file): string
    {
        return $this->file($file)->read();
    }

    public function readStream(string $file)
    {
        return $this->file($file)->readStream();
    }

    public function write(string $file, string $contents, array $options = []): void
    {
        $this->file($file)->setOptions($options)->write($contents);
    }

    public function writeStream(string $file, $stream, array $options = []): void
    {
        $this->file($file)->setOptions($options)->writeStream($stream);
    }

    public function uuid(?string $ext = null)
    {
        return $this->file(uuid4() . ($ext ? '.' . $ext : ''));
    }
}
