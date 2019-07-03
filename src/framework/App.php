<?php

namespace Framework;

use Exception;
use Framework\Http\Router;
use Framework\Queue\Dispatch;
use Framework\Contracts\Cache;
use Framework\Traits\Singleton;
use Framework\Database\Connection;
use Framework\Exceptions\FailedRouteResolveException;

/**
 * Class App
 *
 * @package Framework
 */
class App
{
    use Singleton;

    /**
     *
     */
    private $root;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Cache;
     */
    private $cache;

    /**
     * @var Dispatch
     */
    private $dispatcher;

    /**
     * App constructor.
     */
    public function __construct() {
        $this->root = $_SERVER["PWD"];
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
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
     * @return Cache
     */
    public function getCache(): Cache
    {
        return $this->cache;
    }

    /**
     * @param Cache $cache
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
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
     * @return bool
     */
    public function isDebug(): bool {
        return env('ENVIRONMENT') === 'development' || $_SESSION['debug'];
    }

    /**
     * @return bool
     */
    public function isCli(): bool {
        return php_sapi_name() == 'cli';
    }

    /**
     * Handles requests sent from the browser. The method calls the router resolve
     * method and dispatches a response based on what the router resolves.
     * If the router fails to resolve the handle method returns either a 404 page
     * or a error page depending on the value of isDebug helper.
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->boot();

        try {
            new Dispatch(
                $this->router::resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'])
            );
        } catch (FailedRouteResolveException $e) {
            // Show 404 page or error if debug.
            !isDebug() ? abort() : error($e);
        } catch (Exception $e) {
            // Show 500 page or error if debug.
            if (!isDebug()) {
                fatal();
            } else {
                throw $e;
            }
        }

        exit;
    }

    /**
     *
     */
    public function boot() {
        // Start session
        session_start();

        // Enable error reporting
        if (isDebug()) {
            $whoops = new \Whoops\Run;
            $whoops->appendHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();

            ini_set('display_errors',1); error_reporting(E_ERROR);
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
