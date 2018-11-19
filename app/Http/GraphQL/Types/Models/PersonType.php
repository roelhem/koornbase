<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 05:45
 */

namespace App\Http\GraphQL\Types\Models;


use App\Person;
use App\Types\Name;
use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class PersonType extends ModelType
{

    public $modelClass = Person::class;

    public $description = 'A `Person` models an individual that is known by O.J.V. de Koornbeurs.';


    public function fields()
    {

        return [
            'name' => [
                'description' => 'The name of the `Person`.',
                'type' => GraphQL::type('PersonName!'),
                'resolve' => function(Person $person) {
                    return new Name($person);
                },
                'importance' => 200,
            ],
            'avatar' => [
                'description' => 'An `Avatar` that can be used to represent this `Person` in the UI.',
                'type' => GraphQL::type('Avatar'),
                'importance' => -40,
            ],

            'birthDate' => [
                'description' => 'The `Date` on which this Person was born.',
                'type' => GraphQL::type('Date'),
                'alias' => 'birth_date',
                'importance' => 180,
            ],

            'age' => [
                'description' => 'The current age of the person.',
                'type' => GraphQL::type('Int'),
                'importance' => 179
            ],

            'membershipStatus' => [
                'description' => "The status of the membership at 'O.J.V. de Koornbeurs' for this person at a certain
                                  date.",
                'type' => GraphQL::type('MembershipStatus'),
                'args' => [
                    'at' => [
                        'description' => 'The `Date` for which you want to get the `MembershipStatus` of this `Person`. 
                                          If this argument is omitted or set to `null`, the current date will be used 
                                          instead.',
                        'type' => GraphQL::type('Date'),
                    ]
                ],
                'resolve' => function(Person $person, $args) {
                    return $person->getLastMembershipStatusChange(array_get($args, 'at',null)) ?? $person;
                },
                'importance' => 100,
            ],

            'memberships' => [
                'description' => "The memberships at 'O.J.V. de Koornbeurs' for this `Person`.",
                'type' => GraphQL::type('[Membership]'),
                'eagerLoad' => 'memberships',
                'importance' => 90,
            ],


            'addresses' => [
                'type' => GraphQL::type('[PersonAddress]'),
                'description' => 'The (postal-)addresses of this person.',
                'eagerLoad' => 'addresses',
                'importance' => 80,
            ],
            'emailAddresses' => [
                'type' => GraphQL::type('[PersonEmailAddress]'),
                'description' => 'The E-mail addresses of this person.',
                'eagerLoad' => 'emailAddresses',
                'importance' => 80,
            ],
            'phoneNumbers' => [
                'type' => GraphQL::type('[PersonPhoneNumber]'),
                'description' => 'The Phone-numbers of this person.',
                'eagerLoad' => 'phoneNumbers',
                'importance' => 80,
            ],

            'koornbeursCards' => [
                'description' => "The Koornbeurs-cards that this `Person` currently owns.",
                'type' => GraphQL::type('[KoornbeursCard]'),
                'eagerLoad' => 'cards',
                'importance' => 50,
            ],

            'remarks' => [
                'type' => GraphQL::type('String'),
                'description' => 'Some (optional) remarks about this person.',
                'importance' => -200,
            ]

        ];
    }

    public function connections()
    {
        return [
            'groups' => [
                'to' => 'Group',
                'eagerLoad' => 'groups',
                'importance' => 10,
            ],
            'certificates' => [
                'to' => 'Certificate',
                'eagerLoad' => 'certificates',
                'importance' => 9,
            ]
        ];
    }

    public function orderables()
    {
        return array_merge(parent::orderables(), [
            'firstName' => [
                'description' => 'Orders a list of persons by their first name.',
                'query' => ['name_first','name_last','id'],
                'cursorPattern' => ['name_first' => 'a*','name_last' => 'a*','id' => 'n'],
            ],
            'lastName' => [
                'description' => 'Orders a list of persons by their last name.',
                'query' => ['name_last','name_first','id'],
                'cursorPattern' => ['name_last' => 'a*','name_first' => 'a*','id' => 'n'],
            ],
            'birthDate' => [
                'description' => 'Orders a list of persons using the date on which they were born.',
                'query' => ['birth_date','id'],
                'cursorPattern' => ['birth_date' => 'datetime','id' => 'n'],
            ],
            'membershipStatus' => [
                'description' => 'Orders a list of persons based on the status of the current membership-status.'
            ]
        ]);
    }

    public function filters()
    {
        return [
            'membershipStatus' => [
                'type' => GraphQL::type('MembershipStatusType'),
                'description' => 'Filters the Persons that currently have the provided membership status.'
            ],

            'anyMembershipStatus' => [
                'type' => GraphQL::type('[MembershipStatusType!]'),
                'description' => 'Filters the Persons that have one of the provided membership statuses.'
            ],

            'birthDateBefore' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Filters the persons who were born before the provided date.',
            ],

            'birthDateAfter' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Filters the persons who were born after the provided date.',
            ],

            'inAnyGroup' => [
                'type' => GraphQL::type('[ID!]'),
                'description' => 'Filters the persons that are in at least one of the provided groups.'
            ],

            'notInGroup' => [
                'type' => GraphQL::type('ID'),
                'description' => 'Filters the persons that are not in the group with the provided `ID`.'
            ]
        ];
    }

}