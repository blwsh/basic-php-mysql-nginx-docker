<?php

namespace Framework\Http;

use Framework\Util\Arr;
use Framework\Contracts\App;

/**
 * Class Request
 *
 * Provides useful methods relating to requests. An instantiated instance of this
 * class is passed to every controller method when called.
 *
 * @package Framework
 */
class Request
{
    /**
     * @var array
     */
    protected $method;
    /**
     * @var array
     */
    protected $data;

    /**
     * @var App
     */
    protected $app;

    /**
     * @var array
     */
    protected $server;
    /**
     * @var array
     */
    protected $files;
    /**
     * @var array
     */
    protected $session;
    /**
     * @var array
     */
    protected $cookies;

    /**
     * Request constructor.
     *
     * @param array $inject
     */
    public function __construct($inject = []) {
        $this->data = $_REQUEST;
        $this->app = &app();
        $this->server = $_SERVER;
        $this->session = $_SESSION;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->method = $_SERVER['REQUEST_METHOD'];

        if ($inject) {
            $this->data = array_merge($this->data, $inject);
        }
    }

    /**
     * @param array $inject
     *
     * @return Request
     */
    public static function capture($inject = []) {
        return new static($inject);
    }

    /**
     * @return array
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * @param string $key = null
     *
     * @return mixed
     */
    public function get(string $key = null)
    {
        if ($key) {
            return Arr::get($this->data, $key);
        }

        return $this->data;
    }

    /**
     * @param array $data
     */
    public function inject(array $data) {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * @param string $key = null
     * @param null   $value
     *
     * @return mixed
     */
    public function server(string $key, $value = null)
    {
        if (!is_null($value)) {

        } else if ($key) {
            return Arr::get($this->server, $key);
        }

        return $this->server;
    }

    /**
     * @param string $key
     * @param null   $value
     *
     * @return mixed
     */
    public function files(string $key, $value = null)
    {
        if (!is_null($value)) {

        } else if ($key) {
            return Arr::get($this->files, $key);
        }

        return $this->files;
    }

    /**
     * @param string $key = null
     * @param null   $value
     *
     * @return mixed
     */
    public function session(string $key, $value = null)
    {
        if (!is_null($value)) {
            return Arr::set($this->session, $key, $value);
        } else if ($key) {
            return Arr::get($this->session, $key);
        }

        return $this->session;
    }

    /**
     * @param string $key = null
     * @param        $value
     *
     * @return mixed
     */
    public function cookies(string $key, $value)
    {
        if (!is_null($value)) {
            return Arr::set($this->cookies, $key, $value);
        } else if ($key) {
            return Arr::get($this->cookies, $key);
        }

        return $this->cookies;
    }
}