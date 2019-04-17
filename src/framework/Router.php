<?php

namespace Framework;

/**
 * Class Router
 * @package Framework
 */
class Router
{
    /**
     * @var array
     */
    private static $routes;

    /**
     * @return array
     */
    public static function getRoutes() {
        return self::$routes;
    }

    /**
     * @param $method
     * @param $uri
     *
     * @return mixed
     * @throws Exceptions\InvalidRequestMethod
     */
    public static function resolve($method, $uri)
    {
        $path = parse_url($uri)['path'];

        if (in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            foreach (self::$routes[$method] as $route => $controller) {
                if ($path === '/') {
                    return self::$routes[$method]['/'];
                } else {
                    if (preg_match('/\{\w+(\(\[.+]\))*\}/', $route, $matches)) {
                        if (count($matches) > 1) {
                            array_shift($matches);
                        }
                    }
                }
            }

            exit;
        } else {
            throw new Exceptions\InvalidRequestMethod();
        }
    }

    /**
     * @param $route
     * @param $controller
     */
    public static function get($route, $controller)
    {
        self::$routes['GET'][$route] = $controller;
    }

    /**
     * @param $route
     * @param $controller
     */
    public static function post($route, $controller) {
        self::$routes['POST'][$route] = $controller;
    }

    /**
     * @param $route
     * @param $controller
     */
    public static function put($route, $controller) {
        self::$routes['PUT'][$route] = $controller;
    }

    /**
     * @param $route
     * @param $controller
     */
    public static function delete($route, $controller) {
        self::$routes['DELETE'][$route] = $controller;
    }
}