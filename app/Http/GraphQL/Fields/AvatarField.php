<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 21:32
 */

namespace App\Http\GraphQL\Fields;


use App\Types\AvatarType;
use Rebing\GraphQL\Support\Field;

class AvatarField extends Field
{

    protected $attributes = [
        'name' => 'avatar',
        'description' => 'The avatar that represents the current model.',
        'selectable' => false
    ];

    public function type()
    {
        return \GraphQL::type('Avatar');
    }

    public function resolve($root)
    {
        $avatar = $root->avatar;
        if($avatar instanceof AvatarType) {
            return $avatar->toArray();
        } elseif(is_array($avatar)) {
            return $avatar;
        } else {
            return null;
        }
    }

}