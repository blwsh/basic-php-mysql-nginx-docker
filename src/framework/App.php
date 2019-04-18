<?php

namespace Framework;

use Exception;
use Framework\Exceptions\InvalidRequestMethod;
use ReflectionClass;
use ReflectionException;

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
     * @var App
     */
    private static $instance;

    /**
     * @return App
     */
    public function __invoke()
    {
        return self::getInstance();
    }
    
    /**
     * Allows you to call App methods without first having to call
     * getInstance. 
     *
     * e.g: Instead of App::getInstance()->getConnection() just do App::getConnection()
     *
     * @return App
     */
    public function __callStatic($function, $args) {
        return $this->$function(...$args);
    }

    /**
     * App constructor.
     * 
     * @return void
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new app;
        }

        return self::$instance;
    }

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

    /**
     * @return void
     *
     * @throws InvalidRequestMethod
     * @throws ReflectionException
     */
    public function handle()
    {
        try {
            $resolvedRoute = $this->router::resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        } catch (Exceptions\InvalidRequestMethod $e) {
            return http_response_code(404);
        }

        $controllerMethod = (explode('@', $resolvedRoute));

        $controller = 'App\\Controllers\\' . $controllerMethod[0];
        $method = $controllerMethod[1];

        /** @var Controller $instance */
        $reflection = new ReflectionClass($controller);

        if ($reflection->hasMethod($method)) {
            $methodReflection = $reflection->getMethod($method);
            echo $methodReflection->invoke($reflection->newInstance());
        } else {
            throw new InvalidRequestMethod();
        }
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
