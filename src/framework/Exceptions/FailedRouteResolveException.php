<?php

namespace Framework\Exceptions;

use Exception;

class FailedRouteResolveException extends Exception
{
    protected $message = 'Failed to resolve route';
}