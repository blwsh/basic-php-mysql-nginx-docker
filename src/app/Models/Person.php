<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * Class Person
 * @package App\Models
 *
 * @property int $personid
 * @property string $personname
 * @property string $personphone
 * @property string $personemail
 */
class Person extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_Person';
    /**
     * @var string
     */
    protected $primaryKey = 'personid';
}