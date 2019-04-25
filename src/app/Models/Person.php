<?php

namespace App\Models;

use Framework\Model;

class Person extends Model
{
    protected $table = 'fss_Person';

    protected $primaryKey = 'personid';
}