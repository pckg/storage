<?php

namespace Pckg\Storage;

use League\Flysystem\Config;

class File extends Media
{

    protected string $file;

    public function __construct(string $file, array $options = [])
    {
        $this->file = $file;
        parent::__construct($options);
    }

    public function read(): string
    {
        return $this->requireAdapter()
            ->read($this->file);
    }

    public function readStream()
    {
        return $this->requireAdapter()
            ->readStream($this->file);
    }

    public function write(string $content): self
    {
        $this->requireAdapter()
            ->write($this->file, $content, new Config($this->options));

        return $this;
    }

    public function writeStream($stream): self
    {
        $this->requireAdapter()
            ->writeStream($this->file, $stream, new Config($this->options));

        return $this;
    }

    public function delete()
    {
        $this->requireAdapter()->delete($this->file);
    }

    public function exists(): bool
    {
        return $this->requireAdapter()->fileExists($this->file);
    }
}
