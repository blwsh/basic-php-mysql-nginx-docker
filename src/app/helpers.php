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
 * @return \Framework\Request
 */
function request() {
    return \Framework\Request::capture();
};

/**
 * @param     $response
 * @param int $code
 *
 * @return false|string
 * @throws \Framework\Exceptions\ViewNotFoundException
 */
function response($response, $code = 200) {
    // Inject flash data in to view
    if ($response instanceof \Framework\View) {
        if ($_SESSION['_flash'] && !is_null($_SESSION['_flash'])) {
            $response->inject($_SESSION['_flash']);
            unset($_SESSION['_flash']);
        }

        // Render the view
        return $response->render();
    } else if($response) {
        return jsonResponse($response, $code);
    }
}

/**
 * @param null $data
 * @param int  $code
 *
 * @return false|string
 */
function jsonResponse ($data = null, $code = 200)
{
    header_remove();

    $status = [200 => '200 OK', 400 => '400 Bad Request', 422 => 'Unprocessable Entity', 500 => '500 Internal Server Error'];

    header('Status: '.$status[$code]);
    header("Cache-Control: no-transform,public,max-age=0,s-maxage=0");
    header('Content-Type: application/json');

    http_response_code($code);

    return json_encode($data ?? []);
}

function abort($code = 404) {
    http_response_code($code);
    echo view('pages.404');
    exit;
}

function redirect(string $to, int $responseCode = 302) {
    header("Location: $to", true, $responseCode);
    exit;
}

/**
 * Sets header location to REFERER
 *
 * @param int   $responseCode
 * @param array $data
 */
function back(int $responseCode = 302, $data = []) {
    $_SESSION['_flash'] = $data;
    redirect($_SERVER['HTTP_REFERER'] ?? '/', $responseCode ?? 302);
}

/**
 * @param array $data
 * @param       $key
 * @param null  $default
 *
 * @return mixed
 */
function get(array $data, $key, $default = null)
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
function set(array &$array, $key, $value)
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

/**
 * @param string $key
 * @param mixed  $default
 *
 * @return mixed
 */
function config(string $key = null, $default = null) {
    $config = require __DIR__ . '/../config.php';

    if ($key) {
        return get($config, $key);
    } else if (is_null($default)) {
        return $default;
    }

    return $config;
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
    var_dump($data);
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
 * @param string $data
 */
function display(string $data) {
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

function url(string $string) {
    $root = config('root_dir', null);
    return ($root ? '/' . $root : null) . '/' . trim($string, '/');
}

/**
 * @param $string
 *
 * @return string
 */
function slug(string $string) {
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
function getClassName(string $class) {
    try {
        return (new ReflectionClass($class))->getShortName();
    } catch (ReflectionException $e) {
        return $class;
    }
}

/**
 * @param string $name
 * @param array  $data
 *
 * @return \Framework\View
 */
function view(string $name, array $data = []) {
    return new \Framework\View($name, $data);
}