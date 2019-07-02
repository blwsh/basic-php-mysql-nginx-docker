<?php

namespace App\Jobs;

use Framework\Queue\Queueable;

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