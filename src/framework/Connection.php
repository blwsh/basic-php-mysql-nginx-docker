<?php

namespace Framework;

use PDO;

/**
 * Class Connection
 *
 * There should only ever be one instance of this class throughout the entire
 * application lifecycle. To achieve this, we attach the connection to the App
 * singleton at boot and retrieve it via the app when needed.
 *
 * An example shorthand of retrieving the connection using the app helper (See helpers.php).
 * app()->getConnection()
 *
 * @package Framework
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

    /**
     * @return void
     */
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

    public function __debugInfo()
    {
        return [
            'name' => $this->name
        ];
    }

    public function __sleep()
    {
        return ['connection'];
    }

    public function __wakeup()
    {
        $this->instance;
    }
}

