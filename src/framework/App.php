<?php

namespace Framework;

use const E_ERROR;
use Exception;
use Framework\Traits\Singleton;

/**
 * Class App
 *
 * @package Framework
 */
class App
{
    use Singleton;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Dispatch
     */
    private $dispatcher;

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @return Dispatch
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param Dispatch $dispatcher
     */
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return void
     *
     * @throws Exceptions\ControllerMethodNotFoundException
     * @throws Exceptions\FailedRouteResolveException
     * @throws \ReflectionException
     */
    public function handle()
    {
        $this->boot();

        try {
            new Dispatch(
                $this->router::resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'])
            );
        } catch (Exceptions\InvalidRequestMethod $e) {
            http_response_code(404);
        }

        exit;
    }

    public function boot() {
        // Start session
        session_start();

        // Enable error reporting
        ini_set('display_errors',1); error_reporting(E_ERROR);
    }

    /**
     * Disable the cloning of this class.
     *
     * @return void
     *
     * @throws Exception
     */
    final public function __clone()
    {
        throw new Exception('Feature disabled.');
    }

    /**
     * Disable the wakeup of this class.
     *
     * @return void
     *
     * @throws Exception
     */
    final public function __wakeup()
    {
        throw new Exception('Feature disabled.');
    }
}
