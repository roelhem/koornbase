<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 13:27
 */

namespace App\Http\GraphQL\Types\Avatars;


use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class PersonAvatarType extends ObjectType
{

    public $name = "PersonAvatar";

    public $description = "An `Avatar` that is derived from the data of a `Person`-typed model.";


    public function fields()
    {
        return [
            'person' => [
                'description' => 'The `Person` from which this `PersonAvatar` was derived.',
                'type' => GraphQL::type('Person'),
                'importance' => -8,
            ]
        ];
    }



    public function interfaces()
    {
        return [GraphQL::type('Avatar')];
    }

}