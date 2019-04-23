<?php

namespace Framework;


/**
 * Class Request
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
        $this->server = $_SERVER;
        $this->session = $_SESSION;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;

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
    public function getMethod()
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
            return get($this->data, $key);
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
     *
     * @return mixed
     */
    public function server(string $key = null)
    {
        if ($key) {
            return get($this->server, $key);
        }

        return $this->server;
    }

    /**
     * @param string $key = null
     *
     * @return mixed
     */
    public function files(string $key = null)
    {
        if ($key) {
            return get($this->files, $key);
        }

        return $this->files;
    }

    /**
     * @param string $key = null
     *
     * @return mixed
     */
    public function session(string $key = null)
    {
        if ($key) {
            return get($this->session, $key);
        }

        return $this->session;
    }

    /**
     * @param string $key = null
     *
     * @return mixed
     */
    public function cookies(string $key = null)
    {
        if ($key) {
            return get($this->cookies, $key);
        }

        return $this->cookies;
    }
}