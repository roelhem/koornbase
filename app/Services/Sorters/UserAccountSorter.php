<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:08
 */

namespace App\Services\Sorters;


class UserAccountSorter extends Sorter
{

    protected $columns = [
        'id',
        'provider',
        'expires_in',
        'ref_id',
        'nickname',
        'name',
        'email',
        'created_at',
        'updated_at'
    ];

}