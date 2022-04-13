<?php

namespace Pckg\Storage;

use League\Flysystem\Config;

class File extends Media
{
    protected Directory $directory;

    protected string $file;

    public function __construct(string $file, array $options = [])
    {
        $this->file = $file;
        parent::__construct($options);
    }

    public function getName(): string
    {
        return $this->file;
    }

    public function setDirectory(?Directory $directory)
    {
        $this->directory = $directory;

        return $this;
    }

    public function getDirectory(): ?Directory
    {
        return $this->directory;
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

    public function writeUnique(string $content): self
    {
        return $this->makeUniqueName()
            ->write($content);
    }

    public function writeIfNonExistent(string $content): self
    {
        if ($this->exists()) {
            return $this;
        }

        return $this->write($content);
    }

    public function makeUniqueName()
    {
        return $this;
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
