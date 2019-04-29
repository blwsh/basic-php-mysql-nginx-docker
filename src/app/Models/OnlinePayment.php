<?php

namespace App\Models;

use Framework\Model;

/**
 * Class OnlinePayment
 * @package App\Models
 *
 * @property int $payid
 * @property int $custid
 */
class OnlinePayment extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_OnlinePayment';

    /**
     * @var string
     */
    protected $primaryKey = 'payid';
}