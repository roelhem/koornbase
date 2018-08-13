<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:26
 */

namespace App\GraphQL\Fields\Stamps;


use App\GraphQL\Fields\Stamps\Traits\UserstampIdTrait;
use Rebing\GraphQL\Support\Field;

class DeletedByField extends Field
{

    use UserstampIdTrait;


    protected $attributes = [
        'name' => 'deleted_by',
        'description' => 'The `ID` of the user that (soft)-deleted this object.'
    ];

    protected function resolve($root) {
        return $root->deleted_by;
    }
}