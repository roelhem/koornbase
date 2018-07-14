<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:16
 */

namespace App\GraphQL\Fields\Stamps;

use App\GraphQL\Fields\Stamps\Traits\TimestampTrait;
use Rebing\GraphQL\Support\Field;

class DeletedAtField extends Field
{

    use TimestampTrait;

    protected $attributes = [
        'name' => 'deleted_at',
        'description' => 'The moment on which this entity was (soft)-deleted.'
    ];

    protected function resolve($root)
    {
        return $root->deleted_at;
    }

}