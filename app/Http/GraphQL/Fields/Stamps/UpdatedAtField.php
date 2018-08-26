<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:15
 */

namespace App\Http\GraphQL\Fields\Stamps;

use App\Http\GraphQL\Fields\Stamps\Traits\TimestampTrait;
use Rebing\GraphQL\Support\Field;

class UpdatedAtField extends Field
{

    use TimestampTrait;

    protected $attributes = [
        'name' => 'updated_at',
        'description' => 'The moment on which this entity last updated.'
    ];

    protected function resolve($root)
    {
        return $root->updated_at;
    }

}