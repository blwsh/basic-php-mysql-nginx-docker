<?php

namespace Framework;

use Exception;

/**
 * Class Cache
 * @package Framework
 */
class Cache
{
    /**
     * @param $key
     * @param $dir
     *
     * @return false|string
     */
    public static function get($key, string $dir = null) {
        $dir = rtrim(implode('/', [__DIR__ . '/../cache', $dir]), '/') . '/';

        if (is_dir($dir) && is_writable($dir)) {
            return file_get_contents($dir . crc32($key));
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $dir
     *
     * @return bool
     * @throws Exception
     */
    public static function put(string $key, string $value, string $dir = 'null') {
        $dir = rtrim(implode('/', [__DIR__ . '/../cache', $dir]), '/') . '/';

        if (is_dir($dir) && is_writable($dir)) {
            return file_put_contents($dir . crc32($key), $value) != false;
        } else {
            throw new Exception('Unable to write to cache');
        }
    }
}