<?php

namespace Framework\Util;

/**
 * Class Str
 * @package Framework\Util
 */
class Str
{
    /**
     * @param string $string
     *
     * @return mixed
     */
    public static function dot(string $string) {
        return str_replace('.', '/', $string);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function url(string $string) {
        $root = config('root_dir', null);
        return ($root ? '/' . $root : null) . '/' . trim($string, '/');
    }

    /**
     * @param $string
     *
     * @return string
     */
    public static function slug(string $string) {
        return strtolower(
            trim(
                rtrim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-')
            )
        );
    }
}