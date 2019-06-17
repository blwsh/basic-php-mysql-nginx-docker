<?php

namespace Framework;

use Framework\Exceptions\ControllerMethodNotFoundException;
use ReflectionClass;

/**
 * Class Dispatch
 *
 * Handles the calling of controllers and sending appropriate response.
 *
 * @package Framework
 */
class Dispatch {
    /**
     * Dispatch constructor.
     *
     * @param Route $route
     *
     * @throws \ReflectionException
     * @throws ControllerMethodNotFoundException
     * @throws Exceptions\ViewNotFoundException
     */
    function __construct(Route $route) {
        /** @var Controller $instance */
        $reflection = new ReflectionClass($route->getController());

        if ($reflection->hasMethod($route->getMethod())) {
            $methodReflection = $reflection->getMethod($route->getMethod());

            $request = Request::capture($route->getArgs());

            $result = $methodReflection->invoke($reflection->newInstance(), $request);

            echo response($result, 200);
        } else {
            throw new ControllerMethodNotFoundException("The controller method ($reflection->name::{$route->getMethod()} could not be found.");
        }
    }
}