<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 20:30
 */

namespace App\GraphQL\Fields\Stamps;


use App\GraphQL\Fields\Stamps\Traits\UserstampRelationTrait;
use Rebing\GraphQL\Support\Field;

class CreatorField extends Field
{

    use UserstampRelationTrait;

    protected $attributes = [
        'name' => 'creator',
        'description' => 'Relation to the user that created this object.'
    ];

    protected function resolve($root) {
        return $root->creator;
    }

}