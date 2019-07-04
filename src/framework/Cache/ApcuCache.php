<?php

namespace Framework\Cache;

use Framework\Contracts\Cache;

/**
 * Class Cache
 * @package Framework
 */
class ApcuCache implements Cache
{
    /**
     * @param string $key
     * @param array  $options
     *
     * @return mixed
     */
    public static function get(string $key, array $options = []) {
        return apcu_fetch($key);
    }

    /**
     * @param string     $key
     * @param mixed      $value
     * @param array|null $options
     *
     * @return mixed
     */
    public static function put(string $key, $value, array $options = null) {
        return apcu_add($key, $value);
    }

    /**
     * @return bool
     */
    public static function clear(): bool
    {
        apcu_clear_cache();
    }
}