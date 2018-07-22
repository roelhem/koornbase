<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 08:39
 */

namespace App\GraphQL\Fields\Authorization;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class ViewableField extends Field
{
    protected $attributes = [
        'name' => 'viewable',
        'description' => 'shows if the current user is allowed to view the current model.',
        'selectable' => false
    ];

    public function type()
    {
        return Type::nonNull(Type::boolean());
    }

    public function resolve($root)
    {
        return \Gate::allows('view', $root);
    }
}