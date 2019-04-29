<?php

namespace App\Models;

use Framework\Model;

/**
 * Class OnlinePurchase
 * @package App\Models
 *
 * @property int $fpid
 * @property int $addid
 */
class OnlinePurchase extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_OnlinePurchase';
    /**
     * @var string
     */
    protected $primaryKey = 'fpid';
}