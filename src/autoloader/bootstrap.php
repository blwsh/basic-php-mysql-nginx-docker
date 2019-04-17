<?php

include_once '../app/helpers.php';

spl_autoload_register(function ($class) {
    $file = '../' . lcfirst(str_replace('\\', '/', $class)) . '.php';
    if (file_exists($file)) include_once $file;
});

include_once '../app/routes.php';

