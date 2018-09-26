<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 05:45
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\Person;
use App\Types\Name;
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
                }
            ],

            'birthDate' => [
                'description' => 'The `Date` on which this Person was born.',
                'type' => GraphQL::type('Date'),
                'alias' => 'birth_date'
            ],
            'addresses' => [
                'type' => GraphQL::type('[PersonAddress]'),
            ],
            'emailAddresses' => [
                'type' => GraphQL::type('[PersonEmailAddress]'),
            ],
            'phoneNumbers' => [
                'type' => GraphQL::type('[PersonPhoneNumber]'),
            ],
            'memberships' => [
                'description' => "The memberships at 'O.J.V. de Koornbeurs' for this `Person`.",
                'type' => GraphQL::type('[Membership]'),
            ],
            'membershipStatus' => [
                'description' => "The status of the membership at 'O.J.V. de Koornbeurs' for this person at a certain
                                  date.",
                'type' => GraphQL::type('MembershipStatus!'),
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
                }
            ],
            'koornbeursCards' => [
                'description' => "The Koornbeurs-cards that this `Person` currently owns.",
                'type' => GraphQL::type('[KoornbeursCard]'),
            ],
            'avatar' => [
                'description' => 'An `Avatar` that can be used to represent this `Person` in the UI.',
                'type' => GraphQL::type('Avatar'),
            ],

        ];
    }

    public function connections()
    {
        return [
            'groups' => [
                'to' => 'Group'
            ],
            'certificates' => [
                'to' => 'Certificate'
            ]
        ];
    }

    public function orderables()
    {
        return array_merge(parent::orderables(), [
            'firstName' => [
                'description' => 'Orders a list of persons by their first name.',
                'query' => ['name_first','name_last','id'],
                'cursorPattern' => ['name_first' => 'a','name_last' => 'a','id' => 'n'],
            ],
            'lastName' => [
                'description' => 'Orders a list of persons by their last name.',
                'query' => ['name_last','name_first','id'],
                'cursorPattern' => ['name_last' => 'a','name_first' => 'a','id' => 'n'],
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

}