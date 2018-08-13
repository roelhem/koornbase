<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:01
 */

namespace App\Services\Sorters;


class PersonAddressSorter extends Sorter
{

    protected $columns = [
        'id',
        'index',
        'label',
        'country_code',
        'administrative_area',
        'locality',
        'dependent_locality',
        'postal_code',
        'sorting_code',
        'address_line_1',
        'address_line_2',
        'organisation',
        'created_at',
        'updated_at',
    ];
}