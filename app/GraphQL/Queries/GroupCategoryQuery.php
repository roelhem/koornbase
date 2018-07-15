<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:07
 */

namespace App\GraphQL\Queries;


use App\GroupCategory;

class GroupCategoryQuery extends ModelViewQuery
{

    protected $attributes = [
        'name' => 'groupCategory'
    ];

    protected $typeName = 'GroupCategory';
    protected $slug = true;

    public function query($args, $selectFields)
    {
        return GroupCategory::query();
    }

}