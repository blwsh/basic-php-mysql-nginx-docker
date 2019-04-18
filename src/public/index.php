<?php

/**
 * Autoload.
 */
require_once '../autoloader/bootstrap.php';

/**
 * Start the app.
 */
$app = app();

/**
 * Set the connection for the app.
 */
$app->setConnection(
    new Framework\Connection(
        'magento',
        'db',
        'root',
        'root'
    )
);

/**
 * Add routes for the app.
 */
$app->setRouter(new Framework\Router());

/**
 * Handle the request.
 */
return $app->handle();
