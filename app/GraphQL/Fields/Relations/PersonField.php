<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 03:04
 */

namespace App\GraphQL\Fields\Relations;


use Rebing\GraphQL\Support\Field;

class PersonField extends Field
{

    protected $attributes = [
        'name' => 'person',
        'description' => 'The Person where this object belongs to.'
    ];

    public function type()
    {
        return \GraphQL::type('Person');
    }

}