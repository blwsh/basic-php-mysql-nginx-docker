<?php

namespace Framework\Cache;

use Framework\Contracts\Cache;

/**
 * Class Cache
 * @package Framework
 */
class FilesystemCache implements Cache
{
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
     * @return bool
     */
    public static function clear(): bool
    {

    }
}