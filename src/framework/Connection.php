<?php

namespace Framework;

use PDO;

/**
 * Class Connection
 */
class Connection {
    /**
     * @var PDO
     */
    protected $instance;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $host;
    /**
     * @var string
     */
    protected $user;
    /**
     * @var string
     */
    protected $password;

    /**
     * Connection constructor.
     *
     * @param string $name
     * @param string $host
     * @param string $user
     * @param string $password
     */
    public function __construct($name, $host, $user, $password) {
        $this->name     = $name;
        $this->host     = $host;
        $this->user     = $user;
        $this->password = $password;

        $this->instance = new PDO("mysql:dbname=$this->name;host=$this->host", $this->user, $this->password, []);
        $this->handleConnection();
    }

    private function handleConnection() {
        $this->instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @return PDO
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}

