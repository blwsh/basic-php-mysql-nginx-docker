<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * Class DVDStock
 * @package App\Models
 */
class DVDStock extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_DVDStock';
    /**
     * @var string
     */
    protected $primaryKey = 'filmid';
}