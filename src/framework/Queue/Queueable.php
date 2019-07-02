<?php

namespace Framework\Queue;

use Framework\Traits\LogsToConsole;

abstract class Queueable
{
    use LogsToConsole;

    /**
     * @return mixed
     */
    public abstract function handle();
}