<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:23
 */

namespace App\GraphQL\Fields\Stamps;


use App\GraphQL\Fields\Stamps\Traits\UserstampIdTrait;
use Rebing\GraphQL\Support\Field;

class CreatedByField extends Field
{

    use UserstampIdTrait;

    protected $attributes = [
        'name' => 'created_by',
        'description' => 'The `ID` of the user that created this object.'
    ];

    protected function resolve($root) {
        return $root->created_by;
    }

}