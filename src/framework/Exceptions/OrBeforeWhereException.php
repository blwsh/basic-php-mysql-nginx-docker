<?php

namespace Framework\Framework;

use Exception;

/**
 * Class OrBeforeWhereException
 * @package Framework\Framework
 */
class OrBeforeWhereException extends Exception
{
    /**
     * @var string
     */
    protected $message = "Cannot use or before where";
}