<?php

namespace App\Models;

use Framework\Model;

/**
 * Class Address
 * @package App\Models
 *
 * @property int $addid
 * @property string $addstreet
 * @property string $addcity
 * @property string $addpostocde
 */
class Address extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_Address';

    /**
     * @var string
     */
    protected $primaryKey = 'addid';
}