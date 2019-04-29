<?php

namespace Framework\Exceptions;

use Exception;

/**
 * Class InvalidRequestMethod
 * @package Framework\Exceptions
 */
class InvalidRequestMethod extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Request method invalid';
}