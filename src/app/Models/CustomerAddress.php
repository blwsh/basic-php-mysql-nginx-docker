<?php

namespace App\Models;

use Framework\Model;

/**
 * Class Address
 * @package App\Models
 *
 * @property int $addid
 * @property int $custid
 */
class CustomerAddress extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_CustomerAddress';

    /**
     * @var string
     */
    protected $primaryKey = 'addid';
}