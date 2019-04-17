<?php

function dump($data) {
    echo "<pre>".print_r($data,true)."</pre>";

}

function dd($data) {
    dump($data);

    exit;
}

function display($data) {
    echo "<div style='border: 1px solid; padding: 10px; margin: 10px; font-family: monospace; line-height: 1.5;' '>$data</div>";
}