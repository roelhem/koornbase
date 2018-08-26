<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-07-18
 * Time: 00:35
 */

namespace App\Http\GraphQL\Fields;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class SlugField extends Field
{

    protected $attributes = [
        'name' => 'slug',
        'description' => 'a url-safe string that uniquely identifies a model.'
    ];

    public function type()
    {
        return Type::string();
    }

}