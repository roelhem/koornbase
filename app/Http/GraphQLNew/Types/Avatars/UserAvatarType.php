<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 13:27
 */

namespace App\Http\GraphQLNew\Types\Avatars;


use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class UserAvatarType extends ObjectType
{

    public $name = "UserAvatar";

    public $description = "An `Avatar` that is derived from the data of a `User`-typed model.";


    public function fields()
    {
        return [
            'user' => [
                'description' => 'The `User` from which this `UserAvatar` was derived.',
                'type' => GraphQL::type('User'),
            ]
        ];
    }



    public function interfaces()
    {
        return [GraphQL::type('Avatar')];
    }

}