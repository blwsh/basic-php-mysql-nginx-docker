<?php

namespace Framework\Traits;

use const JSON_PRETTY_PRINT;

trait LogsToConsole
{
    /**
     * @param $string
     */
    protected function info($string) {
        if (is_object($string) || is_array($string)) $string = json_encode($string, JSON_PRETTY_PRINT);
        echo "\e[0;30;42m$string\e[0m\n";
    }

    /**
     * @param $string
     */
    protected function error($string) {
        echo "\e[0;31;42m$string\e[0m\n";
    }
}