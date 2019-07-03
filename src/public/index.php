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
require_once __DIR__ .'/../vendor/autoload.php';

/**
 * Start the app.
 */
$app = app();

/**
 * Set the connection for the app.
 */
$app->setConnection(
    new Framework\Database\Connection(
        config('db.name', 'mvc'),
        config('db.host', 'db'),
        config('db.user', 'root'),
        config('db.password', 'root')
    )
);

/**
 * Set the cache driver. Must implement Framework\Contracts\Cache.
 */
$app->setCache(new Framework\Cache\FilesystemCache());

/**
 * Add routes for the app.
 */
$app->setRouter(new Framework\Http\Router);

/**
 * Handle the request.
 *
 * @noinspection PhpUnhandledExceptionInspection - Caught by flip/whoops package.
 */
$app->handle();
