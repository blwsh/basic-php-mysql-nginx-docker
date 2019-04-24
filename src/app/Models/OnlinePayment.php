<?php

namespace App\Models;

use Framework\Model;

class OnlinePayment extends Model
{
    protected $table = 'fss_OnlinePayment';

    protected $primaryKey = 'payid';
}