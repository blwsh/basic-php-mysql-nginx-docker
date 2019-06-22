<?php

namespace Framework;

use function is_null;/**
 * Class Command
 * Usage:
 * 1. Create a new class in app/commands and extend this class.
 * 2. Implement the abstract handle method.
 * 3. Call the class using command php command <ClassName>
 *   * To pass arguments to the command just add them after <ClassName>
 *     E.g: php command <ClassName> <Arg1> <Arg2>
 * @package Framework
 */
abstract class Command
{
    /**
     * @var
     */
    protected $args;

    /**
     * Command constructor.
     *
     * @param array $args
     */
    public function __construct(array $args) {
        $this->args = $args;
    }

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
        if (!is_null($key)) {
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