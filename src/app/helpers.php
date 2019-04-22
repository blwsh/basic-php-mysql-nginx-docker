<?php

use Framework\App;

/**
 * Returns an instance of the app.
 * @return App
 */
function app() {
    return App::getInstance();
}

/**
 * @param      $array
 * @param      $key
 * @param null $default
 *
 * @return mixed
 */
function get($array, $key, $default = null)
{
    if (is_null($key)) return $array;

    if (isset($array[$key])) return $array[$key];

    foreach (explode('.', $key) as $segment)
    {
        if ( ! is_array($array) ||
            ! array_key_exists($segment, $array))
        {
            return value($default);
        }

        $array = $array[$segment];
    }

    return $array;
}

/**
 * @param $array
 * @param $key
 * @param $value
 *
 * @return mixed
 */
function set(&$array, $key, $value)
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

        $array =& $array[$key];
    }

    $array[array_shift($keys)] = $value;

    return $array;
}


function config($key = null) {
    $config = require '/src/config.php';

    if ($key) {
        return get($config, $key);
    }

    return $config;
}

function env($key = null) {
    if ($key) {
        if (isset($_ENV[$key])) return $_ENV[$key];
    } else {
        return false;
    }

    return $_ENV;
}

function isDebug() {
    return env('ENVIRONMENT') === 'development';
}

/**
 * Dumps data.
 *
 * @param mixed $data
 */
function dump($data) {
    echo "<pre>";
    var_export($data);
    echo "</pre>";
}

/**
 * Dumps data then dies.
 *
 * @param mixed $data
 */
function dd($data) {
    dump($data);
    die;
}

/**
 * Displays data passed as param in a styled div.
 *
 * @param mixed $data
 */
function display($data) {
    echo "<div style='border: 1px solid; padding: 10px; margin: 10px; font-family: monospace; line-height: 1.5;' '>$data</div>";
}

/**
 * @param string $string
 *
 * @return mixed
 */
function dot(string $string) {
    return str_replace('.', '/', $string);
}

function slug($string) {
    return strtolower(
        trim(
            rtrim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-')
        )
    );
}

/**
 * Tries to strip the namespace from a class and just return the name.
 *
 * @param $class
 *
 * @return string
 */
function getClassName($class) {
    try {
        return (new ReflectionClass($class))->getShortName();
    } catch (ReflectionException $e) {
        return $class;
    }
}

function view(string $name, $data = []) {
    return new \Framework\View($name, $data);
}