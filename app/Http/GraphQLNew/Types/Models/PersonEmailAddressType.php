<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 09:26
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\PersonEmailAddress;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class PersonEmailAddressType extends ModelType
{
    public $modelClass = PersonEmailAddress::class;

    public $description = 'The `PersonEmailAddress`-type models the connection and usage of an `EmailAddress` for a `Person`.';

    protected function fields()
    {
        return [
            'emailAddress' => [
                'description' => 'The `EmailAddress` object that is connected to the `Person` trough this
                            `PersonEmailAddress`-model',
                'type' => GraphQL::type('EmailAddress'),
                'resolve' => function(PersonEmailAddress $personEmailAddress) {
                    return $personEmailAddress->getEmailAddress();
                },
            ],
        ];
    }

    public function interfaces()
    {
        return array_merge(parent::interfaces(), [GraphQL::type('PersonContactEntry')]);
    }
}