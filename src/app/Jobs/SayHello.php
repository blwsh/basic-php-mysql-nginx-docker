<?php

namespace App\Jobs;

use function dump;
use Framework\Queueable;

class SayHello extends Queueable
{
    /**
     * @return mixed
     */
    public function handle()
    {
        dump('Hello');
    }
}