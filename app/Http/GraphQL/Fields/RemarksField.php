<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 02:12
 */

namespace App\Http\GraphQL\Fields;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class RemarksField extends Field
{

    protected $attributes = [
        'name' => 'remarks',
        'description' => 'A text-field with additional remarks about the current object.'
    ];

    public function type()
    {
        return Type::string();
    }

}