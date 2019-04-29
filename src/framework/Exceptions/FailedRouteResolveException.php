<?php

namespace Framework\Exceptions;

use Exception;

/**
 * Class FailedRouteResolveException
 * @package Framework\Exceptions
 */
class FailedRouteResolveException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Failed to resolve route';
}