<?php

namespace Framework;

abstract class Queueable
{
    /**
     * @return mixed
     */
    public abstract function handle();
}