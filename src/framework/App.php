<?php

namespace Framework;

/**
 * Class App
 * @package Framework
 */
class App
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * App constructor.
     */
    public function __construct() { }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param mixed $connection
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
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function handle()
    {
        try {
            $resolvedRoute = $this->router::resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        } catch (Exceptions\InvalidRequestMethod $e) {
            return http_response_code(404);
        }

        dd($resolvedRoute);
    }
}