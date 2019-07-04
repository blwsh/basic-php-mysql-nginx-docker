<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * Class Films
 *
 * @property string $filmid
 * @property string $filmtitle
 * @property string $filmdescription
 * @property string $ratid
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

    /**
     * @var array
     */
    public $attributes = [
        'filmid',
        'filmtitle',
        'filmdescription',
        'ratid'
    ];

    /**
     * Table:
     * create table if not exists fss_Film
     * filmid int auto_increment primary key,
     * filmtitle varchar(50) not null,
     * filmdescription varchar(500) null,
     * ratid int not null,
     *
     * Constraints:
     * constraint fss_Film_ibfk_1 foreign key (ratid) references fss_Rating (ratid))
     *
     * Indexes:
     * create index ratid on fss_Film (ratid);
     */
    public $schema = [
        'filmid' => [
            'type' => 'int',
            'increment' => true,
            'primary' => true
        ],
        'filmtitle' => [
            'type' => 'varchar(50)',
            'null' => false
        ],
        'filmdescription' => [
            'type' => 'varchar(500)',
            'null' => true
        ],
        'ratid' => [
            'type' => 'int',
            'null' => false
        ],
    ];
}
