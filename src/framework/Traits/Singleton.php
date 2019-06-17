<?php

namespace Framework\Traits;

/**
 * Trait Singleton
 *
 * This class can be used to implement the singleton design pattern.
 *
 * Singletons are useful because they allow you to access the same instance of
 * something without having to retrieve, reinstate or reconstruct.
 *
 * An example of a class that uses this design pattern is the App class.
 *
 * The app has things like database connection(s) attached upon app start.
 * Without using a singleton design pattern, this app could run in to issues with
 * too many PDO connections.
 *
 * @package Framework\Traits
 */
trait Singleton
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @return self
     */
    public function __invoke()
    {
        return self::getInstance();
    }

    /**
     * Allows you to call singleton methods without first having to call
     * getInstance.
     *
     * e.g: Instead of App::getInstance()->getConnection() just do App::getConnection()
     *
     * @return self
     */
    public static function __callStatic($function, $args) {
        return self::getInstance()->$function(...$args);
    }

    /**
     * App constructor.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}