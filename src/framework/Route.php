<?php

namespace Framework;

/**
 * Class Route
 *
 * @package Framework
 */
class Route
{
    /**
     * @var string
     */
    protected $resolvedRouteAction;
    /**
     * @var string
     */
    protected $controller;
    /**
     * @var
     */
    protected $method;
    /**
     * @var array
     */
    protected $args = [];

    /**
     * Route constructor.
     *
     * @param string $resolvedRouteAction
     * @param array  $args
     */
    public function __construct(string $resolvedRouteAction, $args = null) {
        // Setup
        $this->resolvedRouteAction = $resolvedRouteAction;

        // Add args
        if ($args) {
            $this->args = $args;
        }

        // Set the controller and method props.
        $controllerMethod = (explode('@', $resolvedRouteAction));
        $this->controller = 'App\\Controllers\\' . $controllerMethod[0];
        $this->method = $controllerMethod[1];
    }

    /**
     * @return string
     */
    public function getResolvedRouteAction(): string
    {
        return $this->resolvedRouteAction;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}