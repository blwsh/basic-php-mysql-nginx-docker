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
 * @param $data
 */
function dump($data) {
    echo "<pre>".print_r($data,true)."</pre>";

}

/**
 * Dumps data then dies.
 *
 * @param $data
 */
function dd($data) {
    dump($data);
    die;
}

/**
 * Displays data passed as param in a styled div.
 *
 * @param $data
 */
function display($data) {
    echo "<div style='border: 1px solid; padding: 10px; margin: 10px; font-family: monospace; line-height: 1.5;' '>$data</div>";
}
