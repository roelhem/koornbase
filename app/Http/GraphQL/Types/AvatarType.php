<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 21:24
 */

namespace App\Http\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AvatarType extends GraphQLType
{

    protected $attributes = [
        'name' => 'Avatar',
        'description' => 'Some information that can be used to display an avatar.'
    ];

    public function fields() {
        return [
            'image' => [
                'type' => Type::string(),
                'description' => 'An url with the image of the avatar.'
            ],
            'letters' => [
                'type' => Type::string(),
                'description' => 'Some letters that can be used as a placeholder if there is no image available.'
            ],
            'icon' => [
                'type' => Type::string(),
                'description' => 'An icon that can be used as a placeholder if there is no image available.'
            ],
            'placeholder' => [
                'type' => Type::boolean(),
                'description' => 'If a default placeholder should be used if there is no image available.'
            ],
            'color' => [
                'type' => Type::string(),
                'description' => 'The bootstrap color of the avatar.'
            ]
        ];
    }

}