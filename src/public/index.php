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

$builder = new \Framework\QueryBuilder(app()->getConnection(), 'test');

/**
 * Handle the request.
 */
$app->handle();
