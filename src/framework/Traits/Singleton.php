<?php

namespace Framework\Traits;

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
     * Allows you to call App methods without first having to call
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