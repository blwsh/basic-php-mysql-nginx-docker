<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * Class Payment
 * @package App\Models
 *
 * @property int $payid
 * @property double $amount
 * @property int $shopid
 * @property int $ptid
 */
class Payment extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_Payment';
}