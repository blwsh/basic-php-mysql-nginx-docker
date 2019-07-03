<?php

use Framework\Util\Arr;

/**
 * Returns an instance of the app.
 *
 * @return Framework\App
 */
function app() {
    return Framework\App::getInstance();
}

/**
 * @return Framework\Http\Request
 */
function request() {
    return Framework\Http\Request::capture();
};

/**
 * @param     $response
 * @param int $code
 *
 * @return false|string
 * @throws \Framework\Exceptions\ViewNotFoundException
 */
function response($response, $code = 200) {
    return (new Framework\Http\Response($response, $code))->send();
}

/**
 * @param int $code
 *
 * @return string|void
 */
function abort($code = 404) {
    (new Framework\Http\AbortResponse(null, $code))->send();
}

/**
 * @param int $code
 */
function fatal($code = 500) {
    (new Framework\Http\FatalResponse(null, $code))->send();
}

/**
 * @param string $to
 * @param int    $code
 * @param array  $data
 */
function redirect(string $to, int $code = 302, $data = []) {
    (new Framework\Http\Response($data, $code))->redirect($to, $data);
}

/**
 * Sets header location to REFERER
 *
 * @param int   $responseCode
 * @param array $data
 */
function back(int $responseCode = 302, $data = []) {
    redirect($_SERVER['HTTP_REFERER'] ?? '/', $responseCode ?? 302, $data);
}

/**
 * @param string $key
 * @param mixed  $default
 *
 * @return mixed
 */
function config(string $key = null, $default = null) {
    $config = require __DIR__ . '/../config.php';

    if ($value = Arr::get($config, $key)) {
        return $value;
    } else if (!is_null($default)) {
        return $default;
    }

    // Return the entire config if nothing is passed to function.
    if (is_null($key) && is_null($default)) return $config;

    return false;
}

/**
 * @param null $key
 *
 * @return bool
 */
function env($key = null) {
    if ($key) {
        if (isset($_ENV[$key])) return $_ENV[$key];
    } else {
        return false;
    }

    return $_ENV;
}

/**
 * @return bool
 */
function isDebug(): bool {
    return app()->isDebug();
}

/**
 * Dumps data.
 *
 * @param      $data
 * @param bool $wrap
 */
function dump($data, $wrap = true) {
    if (php_sapi_name() == 'cli') $wrap = false;

    echo $wrap ? "<pre>" : null;
    var_dump($data);
    echo $wrap ? "</pre>" : null;
}

/**
 * Dumps data then dies.
 *
 * @param mixed $data
 */
function dd(...$data) {
    $data = count($data) <= 1 ? $data[0] : $data;
    dump($data);
    die;
}

/**
 * Displays data passed as param in a styled div.
 *
 * @param string $data
 */
function display(string $data) {
    echo "<pre style='border: 1px solid #ddd; padding: 20px; margin: 10px; font-family: monospace; line-height: 1.5;' '>$data</pre>";
}

/**
 * Tries to strip the namespace from a class and just return the name.
 *
 * @param $class
 *
 * @return string
 */
function getClassName(string $class) {
    try {
        return (new ReflectionClass($class))->getShortName();
    } catch (ReflectionException $e) {
        return $class;
    }
}

/**
 * @param string $string
 *
 * @return string
 */
function slug(string $string) {
    return Framework\Util\Str::slug($string);
}

/**
 * @param string $string
 *
 * @return string
 */
function url(string $string) {
    return Framework\Util\Str::url($string);
}

/**
 * @param string $name
 * @param array  $data
 * @param bool   $cache
 *
 * @return Framework\Http\View
 */
function view(string $name, array $data = [], bool $cache = true) {
    return new Framework\Http\View($name, $data, $cache);
}

function cache() {
    return app()->getCache();
}

/**
 * @param Framework\Queue\Queueable $object
 */
function dispatch(Framework\Queue\Queueable $object) {
    Framework\Queue\Queue::dispatch($object);
}