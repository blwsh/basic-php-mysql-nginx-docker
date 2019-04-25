<?php

namespace Framework;

use Framework\Exceptions\ControllerMethodNotFoundException;
use ReflectionClass;


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
            throw new ControllerMethodNotFoundException("The controller method ($controller()->$method()) could not be found.");
        }
    }
}