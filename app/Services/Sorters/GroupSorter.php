<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 05:09
 */

namespace App\Services\Sorters;


class GroupSorter extends Sorter
{

    protected $columns = [
        'id',
        'category_id',
        'slug',
        'name',
        'name_short',
        'description',
        'member_name',
        'is_required',
        'created_at',
        'updated_at'
    ];

}