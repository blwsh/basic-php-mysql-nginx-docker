<?php

namespace Framework\Http;

use JsonSerializable;
use Framework\Exceptions;
use Framework\Exceptions\FailedRouteResolveException;

/**
 * Class Router
 *
 * This class is reasonable for determining which controller method should be
 * called based on the request path.
 *
 * The main method of this class is the public static function resolve.
 *
 * The methods get, post, put and delete are used to add routes to the router.
 *
 * @package Framework
 */
class Router implements JsonSerializable
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
     * @throws FailedRouteResolveException
     */
    public static function resolve($method, $uri)
    {
        $root = config('root_dir');
        $path = '/' . trim(parse_url($uri)['path'], '/') . '/';

        if ($root) {
            $path = '/' . trim(str_replace($root, null, $path), '/') . '/';
        }

        if ($path === '//' || $path == '/' || $path == '') {
            $path = '/';
        }

        if (in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            foreach (self::$routes[$method] as $route => $controller) {
                $originalRoute = $route;
                $route = $route == '/' ? '/' : '/' . trim($route, '/') . '/';

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
                    preg_match_all('/(?<=\{)([\w]+)/', $route, $keys);

                    return new Route(
                        self::$routes[$method][$originalRoute],
                        $keys ? array_combine($keys[0], (array_splice($matches, 1))) : null
                    );
                }
            }
        } else {
            throw new Exceptions\InvalidRequestMethod();
        }

        throw new FailedRouteResolveException();
    }

    /**
     * Adds a GET request route.
     *
     * @param $route
     * @param $controller
     */
    public static function get($route, $controller)
    {
        self::$routes['GET'][$route] = $controller;
    }

    /**
     * Adds a POST request route.
     *
     * @param $route
     * @param $controller
     */
    public static function post($route, $controller) {
        self::$routes['POST'][$route] = $controller;
    }

    /**
     * Adds a PUT request route.
     *
     * @param $route
     * @param $controller
     */
    public static function put($route, $controller) {
        self::$routes['PUT'][$route] = $controller;
    }

    /**
     * Adds a DELETE request route.
     *
     * @param $route
     * @param $controller
     */
    public static function delete($route, $controller) {
        self::$routes['DELETE'][$route] = $controller;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return self::$routes;
    }

    public function __debugInfo()
    {
        self::$routes;
    }
}