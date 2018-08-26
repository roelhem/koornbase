<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:28
 */

namespace App\Http\GraphQL\Fields\Stamps;


use App\Http\GraphQL\Fields\Stamps\Traits\UserstampIdTrait;
use Rebing\GraphQL\Support\Field;

class UpdatedByField extends Field
{

    use UserstampIdTrait;

    protected $attributes = [
        'name' => 'updated_by',
        'description' => 'The `ID` of the user that last edited this object.'
    ];

    protected function resolve($root) {
        return $root->updated_by;
    }

}