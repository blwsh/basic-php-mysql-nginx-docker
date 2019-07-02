<?php

namespace Framework\Contracts;

/**
 * Interface Cache
 * @package Framework\Contracts
 */
interface Cache
{
    /**
     * @param string $key
     * @param array  $options
     *
     * @return mixed
     */
    public static function get(string $key, array $options = []);

    /**
     * @param string $key
     * @param        $value
     * @param array  $options
     *
     * @return mixed
     */
    public static function put(string $key, $value, array $options = []);

    /**
     * @return bool
     */
    public static function clear(): bool;
}