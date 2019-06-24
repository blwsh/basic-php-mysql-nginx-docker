<?php

namespace Framework;

use function dump;
use Exception;
use function serialize;
use function unserialize;

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
     * @return mixed
     */
    public static function get($key, string $dir = null) {
        $dir = rtrim(implode('/', [__DIR__ . '/../cache', $dir]), '/') . '/';

        if (is_dir($dir)) {
            if (is_file($dir . crc32($key))) {
                return unserialize(@file_get_contents($dir . crc32($key)));
            }
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string $dir
     *
     * @return mixed
     */
    public static function put(string $key, $value, string $dir = null) {
        $dir = rtrim(implode('/', [__DIR__ . '/../cache', $dir]), '/') . '/';


        if (is_dir($dir) && is_writable($dir)) {
            file_put_contents($dir . crc32($key), serialize($value));
        }

        return $value;
    }
}