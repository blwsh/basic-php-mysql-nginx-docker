<?php

namespace Framework\Framework;

use Exception;

class OrBeforeWhereException extends Exception
{
    protected $message = "Cannot use or before where";
}