<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\GraphQL\Queries;

use App\Group;
use GraphQL\Type\Definition\Type;

class GroupsQuery extends ModelListQuery
{
    protected  $modelClass = Group::class;


    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [


            'categoryId' => [
                'type' => Type::id(),
                'description' => 'Filters all the Groups that belong to the GroupCategory with the provided id.'
            ],

            'anyCategoryId' => [
                'type' => Type::listOf(Type::id()),
                'description' => 'Filtes all the Groups that belong to a category that has an `ID` in the provided list.'
            ]

        ]);
    }

}