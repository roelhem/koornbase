<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 03:55
 */

namespace App\GraphQL\Fields;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class IdField extends Field
{

    protected $attributes = [
        'name' => 'id',
        'description' => 'The `ID` of the model. This is the primary key that uniquely identifies a model of a specific type.'
    ];

    /** @inheritdoc */
    public function type()
    {
        return Type::nonNull(Type::id());
    }

}