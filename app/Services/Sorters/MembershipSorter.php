<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 09:06
 */

namespace App\Services\Sorters;


class MembershipSorter extends Sorter
{
    protected $columns = [
        'id','application','start','end'
    ];
}