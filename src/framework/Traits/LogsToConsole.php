<?php

namespace Framework\Traits;

use function app;/**
 * Trait LogsToConsole
 * @package Framework\Traits
 */
trait LogsToConsole
{
    /**
     * @param $string
     */
    protected function info($string) {
        if (app()->isCli()) {
            if (is_object($string) || is_array($string)) $string = json_encode($string, JSON_PRETTY_PRINT);
            echo "\e[0;30;42m$string\e[0m\n";
        }
    }

    /**
     * @param $string
     */
    protected function error($string) {
        if (app()->isCli()) {
            echo "\e[0;31;42m$string\e[0m\n";
        }
    }

    /**
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        (new self)->$name(...$arguments);
    }
}