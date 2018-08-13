<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:05
 */

namespace App\GraphQL\Fields\Stamps;


use App\GraphQL\Fields\Stamps\Traits\TimestampTrait;
use Rebing\GraphQL\Support\Field;

class CreatedAtField extends Field
{

    use TimestampTrait;

    protected $attributes = [
        'name' => 'created_at',
        'description' => 'The moment on which this entity was created.'
    ];

    protected function resolve($root) {
        return $root->created_at;
    }

}