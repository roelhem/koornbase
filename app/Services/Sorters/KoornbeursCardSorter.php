<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 05:30
 */

namespace App\Services\Sorters;


class KoornbeursCardSorter extends Sorter
{

    protected $columns = [
        'id',
        'ref',
        'version',
        'activated_at',
        'deactivated_at',
        'created_at',
        'updated_at'
    ];

}