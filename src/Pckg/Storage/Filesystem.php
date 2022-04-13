<?php

namespace Pckg\Storage;

use Pckg\Storage\Exception\StorageNotFound;
use Pckg\Storage\Storage;

class Filesystem
{
    protected array $sources = [];
    protected array $destinations = [];

    public function __construct(array $sources = [], array $destinations = [])
    {
        $this->sources = $sources;
        $this->destinations = $destinations;
    }

    public function storage(string $key): Storage
    {
        if (!($config = $this->sources[$key])) {
            // local storage with $key as dir
            return Storage::createInstance([
                'adapter' => \League\Flysystem\Local\LocalFilesystemAdapter::class,
                'options' => path('storage/' . $key)
            ]);
        }

        return Storage::createInstance($config);
    }

    public static function dir(string $path): Directory
    {
        return resolve(Filesystem::class)
            ->detectDirFromPath($path);
    }

    public static function file(string $path): File
    {
        return resolve(Filesystem::class)
            ->detectFileFromPath($path);
    }

    public function detectDirFromPath(string $fullpath): Directory
    {
        if (!str_ends_with($fullpath, '/')) {
            throw new \Exception('Use detectFileFromPath or add trailing slash');
        }

        $storagePath = path('storage');
        if (!str_starts_with($fullpath, $storagePath)) {
            throw new \Exception('Outer storage not discoverable?');
        }

        $partialPath = rtrim(substr($fullpath, 0, strlen($storagePath)), '/');
        $exploded = collect(explode('/', $partialPath));

        if ($exploded->count() < 1) {
            throw new \Exception('Mountpoint/dir is needed');
        }

        return static::storage($exploded->shift())
            ->dir($exploded->implode('/'));
    }

    public function detectFileFromPath(string $fullpath): File
    {
        if (str_ends_with($fullpath, '/')) {
            throw new \Exception('Use detectDirFromPath');
        }

        return $this->detectDirFromPath(dirname($fullpath) . '/')
            ->file(basename($fullpath));
    }
}
