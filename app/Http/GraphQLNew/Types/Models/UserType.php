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
                'type' => GraphQL::type('String'),
                'importance' => 245,
            ],
            'email' => [
                'description' => "The E-mailadres of the `User` that is used to log-in and send server-related
                                  notifications (like *password requests*, *permission changes*, etc. ).
                                  \n\n E-mails that have no connection the the KoornBase-system itself, shouldn't
                                  be send to this address, but to one of the `PersonEmailAddress`-typed models
                                  connected to the `Person` of this `User`.",
                'type' => GraphQL::type('Email'),
                'importance' => 244,
            ],
            'person' => [
                'description' => "The `Person` that is coupled with this `User`. All data of this user that has nothing
                                  to do with the KoornBase-system is stored at this model.",
                'type' => GraphQL::type('Person'),
                'importance' => 200
            ],
            'avatar' => [
                'description' => "The `Avatar` that can be used to represent this `User`.",
                'type' => GraphQL::type('Avatar'),
                'importance' => -40
            ]
        ];
    }

    public function orderables()
    {
        return array_merge(parent::orderables(), [
            'name' => [
                'description' => 'Orders a user by the name.',
                'query' => ['name','id'],
                'cursorPattern' => ['name' => 'a*','id' => 'n'],
            ],
            'email' => [
                'description' => 'Orders a user by the email.',
                'query' => ['email','id'],
                'cursorPattern' => ['email' => 'a*','id' => 'n'],
            ],
        ]);
    }
}