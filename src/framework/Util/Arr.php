<?php

namespace Framework\Util;

/**
 * Class Arr
 * @package framework\Util
 */
class Arr
{
    /**
     * @var
     */
    protected $array;

    /**
     * @param array $data
     * @param       $key
     * @param null  $default
     *
     * @return mixed
     */
    public static function get(array $data, $key, $default = null)
    {
        if (!is_string($key) || empty($key) || !count($data)) return $default;

        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);
            foreach ($keys as $innerKey) {
                if (!array_key_exists($innerKey, $data)) {
                    return $default;
                }

                $data = $data[$innerKey];
            }

            return $data;
        }

        return array_key_exists($key, $data) ? $data[$key] : $default;
    }

    /**
     * @param $array
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public static function set(array &$array, $key, $value)
    {
        if (is_null($key)) return $array = $value;

        $keys = explode('.', $key);

        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            if ( ! isset($array[$key]) || ! is_array($array[$key]))
            {
                $array[$key] = array();
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Allows you to pluck pluck from an array and/or objects using dot notation.
     * Example:
     *  pluck($array, 'item.attributes.name')
     *
     * @param array       $array
     * @param string|null $key
     *
     * @return array
     */
    public static function pluck(array $array, string $key = null) {
        $indexes = explode('.', $key);

        if (isset($indexes[0])) {
            $plucked = array_column($array, $indexes[0]);

            if (isset($indexes[1])) {
                array_shift($indexes);
                return self::pluck($plucked, implode('.', $indexes));
            } else {
                return $plucked;
            }
        }
    }
}