<?php

namespace App\Models;

use Framework\Model;

/**
 * Class Films
 *
 * @property string filmid
 * @property string filmtitle
 * @property string filmdescription
 * @property string ratid
 */
class Film extends Model {
    /**
     * @var string
     */
    protected $table = 'fss_Film';

    /**
     * @var string
     */
    protected $primaryKey = 'filmid';
}
