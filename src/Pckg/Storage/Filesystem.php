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

    public function storage(string $key)
    {
        if (!($config = $this->sources[$key])) {
            throw new StorageNotFound();
        }

        return Storage::createInstance($config);
    }
}
