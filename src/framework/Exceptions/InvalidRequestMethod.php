<?php

namespace Framework\Exceptions;

use Exception;

class InvalidRequestMethod extends Exception
{
    protected $message = 'Request method invalid';
}