<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 02:10
 */

namespace App\GraphQL\Fields;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class DescriptionField extends Field
{

    public $attributes = [
        'name' => 'description',
        'description' => 'A long string that describes function or usage of the object.'
    ];

    public function type()
    {
        return Type::string();
    }

}