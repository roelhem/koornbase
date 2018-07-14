<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 16:33
 */

namespace App\GraphQL\Types\Inputs;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class HtmlAttributesType extends GraphQLType
{

    protected $inputObject = true;

    protected $attributes = [
        'name' => 'HtmlAttributes',
        'description' => 'Some attribute values that can be added to an html-tag.'
    ];

    public function fields()
    {
        return [
            'translate' => [
                'type' => Type::string()
            ],
            'class' => [
                'type' => Type::string()
            ]
        ];
    }

}