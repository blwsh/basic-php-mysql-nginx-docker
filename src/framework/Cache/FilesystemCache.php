<?php

namespace Framework\Cache;

use function app;
use DirectoryIterator;
use Exception;
use Framework\Contracts\Cache;
use Framework\Traits\LogsToConsole;

/**
 * Class Cache
 * @package Framework
 */
class FilesystemCache implements Cache
{
    use LogsToConsole;

    /**
     * @param $key
     * @param $dir
     *
     * @return mixed
     */
    public static function get(string $key, array $options = []) {

        $dir = rtrim(implode('/', [app()->getRoot() . '/cache', $options['dir'] ?? null]), '/') . '/';

        if (is_dir($dir)) {
            if (is_file($dir . crc32($key))) {
                return unserialize(@file_get_contents($dir . crc32($key)));
            }
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param array  $options
     *
     * @return mixed
     */
    public static function put(string $key, $value, array $options = []) {
        $dir = rtrim(implode('/', [app()->getRoot() . '/cache', $options['dir'] ?? null]), '/') . '/';

        if (is_dir($dir) && is_writable($dir)) {
            file_put_contents($dir . crc32($key), serialize($value));
        }

        return $value;
    }

    /**
     * @param int                    $minutes
     * @param DirectoryIterator|null $iterator
     *
     * @return bool
     */
    public static function clear(int $minutes = null, DirectoryIterator $iterator = null): bool
    {
        try {
            if (!$iterator) {
                $iterator = new DirectoryIterator(app()->getRoot() . '/cache');
            }

            self::info('Scanning ' . $iterator->getRealPath());

            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isFile()) {
                    $minutes = (int)$minutes[0] ?? 0;
                    if ($fileInfo->getFilename() != '.gitignore' && time() - $fileInfo->getCTime() >= $minutes * 60) {
                        self::info(' - Removing ' . $fileInfo->getFilename());
                        unlink($fileInfo->getRealPath());
                    }
                } else if ($fileInfo->isDir() && !$fileInfo->isDot()) {
                    self::clear($minutes, new DirectoryIterator($fileInfo->getRealPath()));
                } else {
                    continue;
                }
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}