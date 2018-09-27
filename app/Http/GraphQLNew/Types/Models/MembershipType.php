<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 10:26
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\Membership;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class MembershipType extends ModelType
{
    public $modelClass = Membership::class;

    public $name = 'Membership';

    public $description = 'The `Membership`-type models one Koornbeurs-membership of a `Person` from the application
                           to the membership, till the discontinuing of that membership. To model an intermittent
                           membership, multiple `Membership`-models should be used.';

    protected function fields()
    {
        return [
            'person' => [
                'description' => 'The `Person` of this `Membership`.',
                'type' => GraphQL::type('Person'),
                'importance' => 200,
            ],

            'application' => [
                'description' => 'The date of the application for the membership.',
                'type' => GraphQL::type('Date'),
                'importance' => 5,
            ],
            'hasApplied' => [
                'description' => 'Whether or not the *application* has been taken place at a certain date.',
                'type' => GraphQL::type('Boolean'),
                'args' => [
                    'at' => [
                        'description' => 'The `Date` on which you want to determine if the *application* had already
                                          been taken place. If this argument is omitted or set to `null`, the current
                                          date will be used instead.',
                        'type' => GraphQL::type('Date'),
                    ]
                ],
                'importance' => 15,
            ],

            'start' => [
                'description' => 'The date on which this `Membership` was (fully) started.',
                'type' => GraphQL::type('Date'),
                'importance' => 4,
            ],
            'hasStarted' => [
                'description' => 'Whether or not the `Membership` has been *started* at a certain date.',
                'type' => GraphQL::type('Boolean'),
                'args' => [
                    'at' => [
                        'description' => 'The `Date` on which you want to determine if the `Membership` has *started*. 
                                          If this argument is omitted or set to `null`, the current date will be used 
                                          instead.',
                        'type' => GraphQL::type('Date'),
                    ]
                ],
                'importance' => 14,
            ],

            'end' => [
                'description' => 'The date on which this `Membership` had been ended.',
                'type' => GraphQL::type('Date'),
                'importance' => 3,
            ],
            'hasEnded' => [
                'description' => 'Whether or not the `Membership` has *ended* at a certain date.',
                'type' => GraphQL::type('Boolean'),
                'args' => [
                    'at' => [
                        'description' => 'The `Date` on which you want to determine if the `Membership` has *ended*. 
                                          If this argument is omitted or set to `null`, the current date will be used 
                                          instead.',
                        'type' => GraphQL::type('Date'),
                    ]
                ],
                'importance' => 13,
            ],

            'status' => [
                'description' => 'The `MembershipStatus` that this `Membership` caused for the `Person` at a certain
                                  date.',
                'type' => GraphQL::type('MembershipStatus'),
                'args' => [
                    'at' => [
                        'description' => 'The `Date` for which you want to find get the caused `MembershipStatus`. 
                                          If this argument is omitted or set to `null`, the current date will be used 
                                          instead.',
                        'type' => GraphQL::type('Date'),
                    ]
                ],
                'importance' => 100,
            ]
        ];
    }
}