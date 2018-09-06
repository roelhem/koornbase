<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 12:43
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class GroupFilterType extends FilterType
{

    public function filters()
    {
        return [
            'categoryId' => [
                'type' => Type::id(),
                'description' => 'Filters all the Groups that belong to the GroupCategory with the provided id.'
            ],

            'anyCategoryId' => [
                'type' => Type::listOf(Type::id()),
                'description' => 'Filtes all the Groups that belong to a category that has an `ID` in the provided list.'
            ]
        ];
    }

}