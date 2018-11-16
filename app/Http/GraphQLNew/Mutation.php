<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 14:20
 */

namespace App\Http\GraphQLNew;


use App\Actions\Models\Create\CreateGroupAction;
use App\Actions\Models\Create\CreatePersonAction;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Fields\ActionField;
use Roelhem\GraphQL\Types\MutationType;

class Mutation extends MutationType
{

    /**
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    protected function fields()
    {
        return [

            new ActionField(CreatePersonAction::class, [
                'type' => GraphQL::type('Person'),
                'args' => [
                    'name_first' => [
                        'description' => 'The first name (Dutch:"voornaam") of the new `Person`.',
                        'type' => GraphQL::type('String'),
                        'rules' => ['required','string','max:255']
                    ],
                    'name_middle' => [
                        'description' => 'The additional names (Dutch:"tussennamen") of the new `Person`.',
                        'type' => GraphQL::type('String'),
                        'rules' => ['nullable','string','max:255'],
                    ],
                    'name_prefix' => [
                        'description' => 'The prefix of the last name (Dutch:"tussenvoegsel") of the new `Person`.',
                        'type' => GraphQL::type('String'),
                        'rules' => ['nullable','string','max:63'],
                    ],
                    'name_last' => [
                        'description' => 'The last name (Dutch:"achternaam") of the new `Person`. This should NOT include the prefix that is common in Dutch last names (like "van", "de", "der", etc. )',
                        'type' => GraphQL::type('String!'),
                        'rules' => ['required','string','max:255']
                    ],
                    'name_initials' => [
                        'description' => 'The initials (Dutch:"voorletters") of the new `Person`.',
                        'type' => GraphQL::type('String'),
                        'rules' => ['nullable','string','max:63'],
                    ],
                    'name_nickname' => [
                        'description' => 'The nickname (Dutch:"bijnaam")of the new `Person`. This should be the nickname of this `Person` that is commonly used by the members of the Koornbeurs.',
                        'type' => GraphQL::type('String'),
                        'rules' => ['nullable','string','max:63'],
                    ],
                    'birth_date' => [
                        'description' => 'The date on which the new `Person` was born.',
                        'type' => GraphQL::type('Date'),
                        'rules' => ['nullable','date','before:now']
                    ],
                    'remarks' => [
                        'description' => 'Some extra remarks about this `Person`.',
                        'type' => GraphQL::type('String'),
                        'rules' => ['nullable','string'],
                    ]
                ]
            ]),
            new ActionField(CreateGroupAction::class, ['type' => GraphQL::type('Group')]),

        ];
    }
}