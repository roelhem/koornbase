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

    protected $modelClass = GroupCategory::class;
    protected $slug = true;

}