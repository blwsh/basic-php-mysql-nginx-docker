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
     */
    function __construct(Route $route) {
        /** @var Controller $instance */
        $reflection = new ReflectionClass($route->getController());

        if ($reflection->hasMethod($route->getMethod())) {
            $methodReflection = $reflection->getMethod($route->getMethod());

            // Build request
            $request = Request::capture($route->getArgs());

            $result = $methodReflection->invoke($reflection->newInstance(), $request);

            if (is_a($result, View::class)) {
                echo $result->render();
            } else {
                header('Content-Type: application/json');
                echo json_encode($result, JSON_PRETTY_PRINT);
            }
        } else {
            throw new ControllerMethodNotFoundException("The controller method ($controller()->$method()) could not be found.");
        }
    }
}