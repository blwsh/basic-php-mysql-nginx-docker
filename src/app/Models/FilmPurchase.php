<?php

namespace App\Models;

use Framework\Model;

/**
 * Class FilmPurchase
 * @package App\Models
 *
 * @property int $payid
 * @property int $filmid
 * @property int $shopid
 * @property int $price
 */
class FilmPurchase extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_FilmPurchase';

    /**
     * @var string
     */
    protected $primaryKey = 'fpid';
}