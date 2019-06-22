<?php

namespace App\Commands;

use DirectoryIterator;
use Framework\Command;

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
        $this->clearDirectory(new DirectoryIterator(__DIR__ . '/../../cache'));
    }

    /**
     * @param DirectoryIterator $iterator
     */
    private function clearDirectory(DirectoryIterator $iterator)
    {
        $this->info('Scanning ' . $iterator->getRealPath());

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $minutes = (int) $this->args[0] ?? 0;
                if ($fileInfo->getFilename() != '.gitignore' && time() - $fileInfo->getCTime() >= $minutes * 60) {
                    $this->info(' - Removing ' . $fileInfo->getFilename());
                    unlink($fileInfo->getRealPath());
                }
            } else if ($fileInfo->isDir() && !$fileInfo->isDot()) {
                $this->clearDirectory(new DirectoryIterator($fileInfo->getRealPath()));
            } else {
                continue;
            }
        }
    }
}