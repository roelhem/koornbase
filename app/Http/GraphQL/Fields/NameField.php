<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 02:03
 */

namespace App\Http\GraphQL\Fields;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class NameField extends Field
{

    protected $attributes = [
        'name' => 'name',
        'description' => 'The (full) name of the object that is safe to display to the user.'
    ];

    /** @inheritdoc */
    public function type()
    {
        return Type::nonNull(Type::string());
    }

}