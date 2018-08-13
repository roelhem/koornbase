<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:05
 */

namespace App\Services\Sorters;


class PersonPhoneNumberSorter extends Sorter
{

    protected $columns = [
        'id',
        'index',
        'label',
        'phone_number',
        'country_code',
        'created_at',
        'updated_at'
    ];

}