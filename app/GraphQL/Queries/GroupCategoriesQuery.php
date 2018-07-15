<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\GraphQL\Queries;

use App\GroupCategory;

class GroupCategoriesQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'group_categories'
    ];

    protected $typeName = 'GroupCategory';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return GroupCategory::query();
    }

}