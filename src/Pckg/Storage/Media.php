<?php

namespace Pckg\Storage;

use League\Flysystem\FilesystemAdapter;
use Pckg\Storage\Exception\FileAdapterNotSet;

abstract class Media
{
    protected array $options = [];

    protected FilesystemAdapter $adapter;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function setAdapter(FilesystemAdapter $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function requireAdapter(): FilesystemAdapter
    {
        if (!isset($this->adapter)) {
            throw new FileAdapterNotSet();
        }

        return $this->adapter;
    }
}
