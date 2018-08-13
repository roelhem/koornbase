<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:04
 */

namespace App\Services\Sorters;


class PersonEmailAddressSorter extends Sorter
{

    protected $columns = [
        'id',
        'index',
        'label',
        'email_address',
        'created_at',
        'updated_at'
    ];

}