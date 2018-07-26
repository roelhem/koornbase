<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:10
 */

namespace App\Services\Sorters;


class DebtorSorter extends Sorter
{


    protected $columns = [
        'id',
        'exact_ref',
        'created_at',
        'created_by'
    ];

}