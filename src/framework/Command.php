<?php

namespace Framework;

/**
 * Class Command
 * @package Framework
 */
abstract class Command
{
    /**
     * @var
     */
    protected $args;

    /**
     * @return mixed
     */
    public abstract function handle();

    /**
     * @param $args $args
     *
     * @return void
     */
    public function setArgs(array $args) {
        $this->args  = $args;
    }

    /**
     * @param string|int $key
     *
     * @return mixed
     */
    public function argument($key = null) {
        if ($key) {
            return get($this->args, $key);
        }

        return $this->args;
    }

    /**
     * @param $string
     */
    protected function info($string) {
        echo "\e[0;30;42m$string\e[0m\n";
    }

    /**
     * @param $string
     */
    protected function error($string) {
        echo "\e[0;31;42m$string\e[0m\n";
    }
}