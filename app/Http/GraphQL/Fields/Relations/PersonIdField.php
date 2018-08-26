<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 03:02
 */

namespace App\Http\GraphQL\Fields\Relations;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class PersonIdField extends Field
{

    protected $attributes = [
        'name' => 'person_id',
        'description' => 'The `ID` of the Person where this object belongs to.',
    ];

    public function type()
    {
        return Type::nonNull(Type::id());
    }

}