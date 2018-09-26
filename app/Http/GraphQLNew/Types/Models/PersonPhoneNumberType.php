<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 08:19
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\PersonPhoneNumber;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class PersonPhoneNumberType extends ModelType
{

    public $modelClass = PersonPhoneNumber::class;

    public $name = 'PersonPhoneNumber';

    public $description = 'The `PersonPhoneNumber`-type models the connection and usage of a `PhoneNumber` of a `Person`.';

    protected function fields()
    {
        return [
            'phoneNumber' => [
                'description' => "The `PhoneNumber` that is associated to the `Person` via this model.",
                'type' => GraphQL::type('PhoneNumber'),
                'resolve' => function(PersonPhoneNumber $personPhoneNumber) {
                    return $personPhoneNumber->phone_number;
                }
            ],
        ];
    }

    public function interfaces()
    {
        return array_merge(parent::interfaces(), [GraphQL::type('PersonContactEntry')]);
    }
}