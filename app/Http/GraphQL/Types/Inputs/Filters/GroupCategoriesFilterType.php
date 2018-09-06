<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:31
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class GroupCategoriesFilterType extends FilterType
{

    public function filters()
    {
        return [
            'style' => [
                'type' => Type::string(),
                'description' => 'Filters all the GroupCategories with the provided style.'
            ]
        ];
    }

}