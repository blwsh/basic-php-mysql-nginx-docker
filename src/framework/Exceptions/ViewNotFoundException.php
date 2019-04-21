<?php

namespace Framework\Exceptions;

use Exception;

class ViewNotFoundException extends Exception
{
    protected $message = 'Unable to find view.';
}