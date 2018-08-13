<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:36
 */

namespace App\GraphQL\Fields\Stamps;


use App\GraphQL\Fields\Stamps\Traits\UserstampRelationTrait;
use Rebing\GraphQL\Support\Field;

class DestroyerField extends Field
{

    use UserstampRelationTrait;

    protected $attributes = [
        'name' => 'destroyer',
        'description' => 'Relation to the user that edited this field.'
    ];

}