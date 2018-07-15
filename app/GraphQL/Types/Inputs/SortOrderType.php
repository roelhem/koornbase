<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 05:22
 */

namespace App\GraphQL\Types\Inputs;

use App\GraphQL\Enums\SortOrderDirectionEnum;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SortOrderType extends GraphQLType
{

    protected $inputObject = true;

    protected $attributes = [
        'name' => 'SortOrder',
        'description' => 'An input object that can be used configure the ordering of a list of models.'
    ];

    public function fields()
    {
        return [
            'field' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the field to order by.'
            ],
            'direction' => [
                'type' => GraphQL::type('SortOrderDirection'),
                'description' => 'The direction on which the sort should be performed.',
                'defaultValue' => SortOrderDirectionEnum::ASC
            ]
        ];
    }

}