<?php

namespace Framework\Exceptions;

use Exception;

/**
 * Class ControllerMethodNotFoundException
 * @package Framework\Exceptions
 */
class ControllerMethodNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'The controller method could not be found';
}