<?php

/**
 * This file's responsibility is to dynamically include the PHP files for classes
 * you using in this project.
 *
 * Helpers and routes are also included here.
 */

include_once __DIR__ . '/../app/helpers.php';

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../' . lcfirst(str_replace('\\', '/', $class)) . '.php';
    if (file_exists($file)) include_once $file;
});

include_once __DIR__ . '/../app/routes.php';

