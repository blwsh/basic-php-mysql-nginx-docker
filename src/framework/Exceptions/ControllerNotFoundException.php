<?php

namespace Framework\Exceptions;

use Exception;

class ControllerNotFoundException extends Exception
{
    protected $message = 'The controller could not be found';
}