<?php

/**
 * Enjingks MVC
 *
 * A framework created for Huddersfield University Databases and Web Development
 * module, assignment 2.
 *
 * @author Ben Watson
 *
 * @version 1.0.0
 */

/**
 * Autoload.
 */
require_once __DIR__ .'/../autoloader/bootstrap.php';

/**
 * Start the app.
 */
$app = app();

/**
 * Set the connection for the app.
 */
$app->setConnection(
    new Framework\Connection(
        config('db.name', 'mvc'),
        config('db.host', 'db'),
        config('db.user', 'root'),
        config('db.password', 'root')
    )
);

/**
 * Add routes for the app.
 */
$app->setRouter(new Framework\Router());

/**
 * Handle the request.
 */
$app->handle();
