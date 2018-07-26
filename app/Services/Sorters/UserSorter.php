<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:07
 */

namespace App\Services\Sorters;


class UserSorter extends Sorter
{

    protected $columns = [
        'id',
        'name',
        'email',
        'created_at',
        'updated_at'
    ];

}