<?php

namespace Pckg\Storage\Provider;

use Pckg\Framework\Config;
use Pckg\Framework\Provider;
use Pckg\Storage\Filesystem;

class Storage extends Provider
{
    public function services()
    {
        return [
            Filesystem::class => function (Config $config) {
                return new Filesystem($config->get('pckg.storage.source', []), $config->get('pckg.storage.mount', []));
            }
        ];
    }
}
