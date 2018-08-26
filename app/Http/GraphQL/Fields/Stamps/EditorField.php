<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:34
 */

namespace App\Http\GraphQL\Fields\Stamps;


use App\Http\GraphQL\Fields\Stamps\Traits\UserstampRelationTrait;
use Rebing\GraphQL\Support\Field;

class EditorField extends Field
{

    use UserstampRelationTrait;

    protected $attributes = [
        'name' => 'editor',
        'description' => 'Relation to the user that edited this field.'
    ];


}