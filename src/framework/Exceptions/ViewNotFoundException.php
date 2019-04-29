<?php

namespace Framework\Exceptions;

use Exception;

/**
 * Class ViewNotFoundException
 * @package Framework\Exceptions
 */
class ViewNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Unable to find view.';
}