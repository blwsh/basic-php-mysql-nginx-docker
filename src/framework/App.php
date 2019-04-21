<?php

namespace Framework;

use Exception;
use function get_class;
use function is_a;
use function is_object;
use function json_encode;
use ReflectionClass;
use ReflectionException;
use Framework\Traits\Singleton;
use Framework\Exceptions\InvalidRequestMethod;

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
     * @var Dispatcher
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
            http_response_code(404);
        }

        /** @var string $resolvedRoute */
        $controllerMethod = (explode('@', $resolvedRoute));

        $controller = 'App\\Controllers\\' . $controllerMethod[0];
        $method = $controllerMethod[1];

        /** @var Controller $instance */
        $reflection = new ReflectionClass($controller);

        if ($reflection->hasMethod($method)) {
            $methodReflection = $reflection->getMethod($method);

            $result = $methodReflection->invoke($reflection->newInstance());

            if (is_a($result, View::class)) {
                echo $result->render();
            } else {
                header('Content-Type: application/json');
                echo json_encode($result);
            }
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
