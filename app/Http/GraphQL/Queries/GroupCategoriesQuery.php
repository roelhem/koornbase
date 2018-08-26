<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\Http\GraphQL\Queries;

use App\GroupCategory;
use GraphQL\Type\Definition\Type;

class GroupCategoriesQuery extends ModelListQuery
{

    protected $modelClass = GroupCategory::class;


    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [

            'style' => [
                'type' => Type::string(),
                'description' => 'Filters all the GroupCategories with the provided style.'
            ]

        ]);
    }

}