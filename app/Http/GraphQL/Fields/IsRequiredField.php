<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 02:27
 */

namespace App\Http\GraphQL\Fields;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class IsRequiredField extends Field
{

    protected $attributes = [
        'name' => 'is_required',
        'description' => 'If this value is true, this object is required by the system and therefore can\'t be deleted.'
    ];

    public function type()
    {
        return Type::nonNull(Type::boolean());
    }

}