<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 04:04
 */

namespace App\GraphQL\Fields;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class CountryField extends Field
{

    protected $attributes = [
        'name' => 'country',
        'description' => 'The full name of the country where his object belongs to.',
        'selectable' => false
    ];

    public function type()
    {
        return Type::nonNull(Type::string());
    }

}