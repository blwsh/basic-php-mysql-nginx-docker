<?php

namespace Framework;

use function array_combine;
use function count;
use Framework\Exceptions\FailedRouteResolveException;

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
     * @throws Exceptions\FailedRouteResolveException
     */
    public static function resolve($method, $uri)
    {
        $path = parse_url($uri)['path'];

        if (in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            foreach (self::$routes[$method] as $route => $controller) {
                // Set up for building regex string
                $regex = preg_replace('/\//', '\/', $route);

                // Replace selectors with no regex
                $regex = preg_replace_callback(
                    '/\{[\w\d]+\}/',
                    function($regex) {
                        return '(.*)';
                    },
                    $regex
                );

                // Replace selectors with regex
                $regex = preg_replace_callback(
                    '/\{\w+(\(.*\))\}/U',
                    function($regex) {
                        if (preg_match('/\{[\w\d]+\((.*)\)\}/U', $regex[0], $matches)) {
                            return "($matches[1])";
                        }
                    },
                    $regex
                );

                $regex = "/^" . $regex . "$/m";

                if (preg_match($regex, $path, $matches)) {
                    preg_match('/(?<=\{)([\w]+)/', $route, $keys);

                    return new Route(
                        self::$routes[$method][$route],
                        $keys ? array_combine($keys, $matches) : null
                    );
                }
            }
        } else {
            throw new Exceptions\InvalidRequestMethod();
        }

        throw new FailedRouteResolveException();
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