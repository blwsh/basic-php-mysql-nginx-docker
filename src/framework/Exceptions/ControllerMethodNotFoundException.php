<?php

namespace Framework\Exceptions;

use Exception;

class ControllerMethodNotFoundException extends Exception
{
    protected $message = 'The controller method could not be found';
}