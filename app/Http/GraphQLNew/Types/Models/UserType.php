<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 07:16
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\User;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class UserType extends ModelType
{

    public $modelClass = User::class;

    public $name = 'User';

    public $description = 'The `User`-type models any \'user\' (not necessarily human) that interacts with the
                           *KoornBase*.';

    protected function fields()
    {
        return [
            'name' => [
                'description' => "The username of the `User` that can be used to identify the user.",
                'type' => GraphQL::type('String!')
            ],
            'email' => [
                'description' => "The E-mailadres of the `User` that is used to log-in and send server-related
                                  notifications (like *password requests*, *permission changes*, etc. ).
                                  \n\n E-mails that have no connection the the KoornBase-system itself, shouldn't
                                  be send to this address, but to one of the `PersonEmailAddress`-typed models
                                  connected to the `Person` of this `User`.",
                'type' => GraphQL::type('Email!'),
            ],
            'person' => [
                'description' => "The `Person` that is coupled with this `User`. All data of this user that has nothing
                                  to do with the KoornBase-system is stored at this model.",
                'type' => GraphQL::type('Person'),
            ],
            'avatar' => [
                'description' => "The `Avatar` that can be used to represent this `User`.",
                'type' => GraphQL::type('Avatar'),
            ]
        ];
    }
}