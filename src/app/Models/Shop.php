<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * Class Shop
 * @package App\Models
 *
 * @property
 */
class Shop extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_Shop';
    /**
     * @var string
     */
    protected $primaryKey = 'shopid';
}