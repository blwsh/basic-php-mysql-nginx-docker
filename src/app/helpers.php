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

function view(string $name, array $data = []) {
    return new \Framework\View($name, $data);
}