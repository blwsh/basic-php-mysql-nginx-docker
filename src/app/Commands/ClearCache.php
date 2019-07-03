<?php

namespace App\Commands;

use Framework\Command;
use Framework\Cache\FilesystemCache;

/**
 * Class ClearCache
 * @package App\Commands
 */
class ClearCache extends Command
{
    /**
     * @return mixed|void
     */
    public function handle()
    {
        $this->info('Clearing cache.');
        (FilesystemCache::clear()) ?
            $this->info('Cache cleared') :
            $this->error('Unable to clear cache.');
    }
}