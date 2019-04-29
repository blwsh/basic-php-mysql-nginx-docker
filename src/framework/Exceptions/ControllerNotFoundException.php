<?php

namespace Framework\Exceptions;

use Exception;

/**
 * Class ControllerNotFoundException
 * @package Framework\Exceptions
 */
class ControllerNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'The controller could not be found';
}