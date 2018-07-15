<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 02:06
 */

namespace App\GraphQL\Fields;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class NameShortField extends Field
{

    protected $attributes = [
        'name' => 'name_short',
        'description' => 'A short representation of the name of this object. Can be used in user interfaces where there is little space.'
    ];

    public function type()
    {
        return Type::nonNull(Type::string());
    }

    public function resolve($root)
    {
        return $root->name_short ?? $root->name;
    }
}