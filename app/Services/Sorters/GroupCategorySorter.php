<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 05:27
 */

namespace App\Services\Sorters;


class GroupCategorySorter extends Sorter
{
    protected $columns = [
        'id',
        'slug',
        'name',
        'name_short',
        'description',
        'style',
        'is_required',
        'created_at',
        'updated_at'
    ];
}