<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-10-18
 * Time: 07:20
 */

namespace App\Actions\Models\Create;

use Roelhem\GraphQL\Facades\GraphQL;

class CreatePersonAction extends AbstractCreateAction
{

    protected $description = "Creates and saves a new Person.";

    public function args()
    {
        return [
            'firstName' => [
                'description' => 'The first name (Dutch:"voornaam") of the new `Person`.',
                'alias' => 'name_first',
                'type' => GraphQL::type('String!'),
                'rules' => ['required','string','max:255']
            ],
            'middleName' => [
                'description' => 'The additional names (Dutch:"tussennamen") of the new `Person`.',
                'alias' => 'name_middle',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:255'],
            ],
            'prefixName' => [
                'description' => 'The prefix of the last name (Dutch:"tussenvoegsel") of the new `Person`.',
                'alias' => 'name_prefix',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'lastName' => [
                'description' => 'The last name (Dutch:"achternaam") of the new `Person`. This should NOT include the prefix that is common in Dutch last names (like "van", "de", "der", etc. )',
                'alias' => 'name_last',
                'type' => GraphQL::type('String!'),
                'rules' => ['required','string','max:255']
            ],
            'initials' => [
                'description' => 'The initials (Dutch:"voorletters") of the new `Person`.',
                'alias' => 'name_initials',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'nickname' => [
                'description' => 'The nickname (Dutch:"bijnaam") of the new `Person`. This should be the nickname of this `Person` that is commonly used by the members of the Koornbeurs.',
                'alias' => 'name_nickname',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'birthDate' => [
                'description' => 'The date on which the new `Person` was born.',
                'alias' => 'birth_date',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','before:now']
            ],
            'remarks' => [
                'description' => 'Some extra remarks about this `Person`.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ]
        ];
    }

}