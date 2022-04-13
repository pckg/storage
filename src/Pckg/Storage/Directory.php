<?php

namespace Pckg\Storage;

class Directory extends Media
{
    protected string $dir;

    public function __construct(string $dir, array $options = [])
    {
        $this->dir = $dir;
        parent::__construct($options);
    }

    public function delete()
    {
        $this->requireAdapter()->deleteDirectory($this->dir);
    }

    public function file(string $file)
    {
        return (new File($file))
            ->setDirectory($this)
            ->setAdapter($this->requireAdapter());
    }
}
