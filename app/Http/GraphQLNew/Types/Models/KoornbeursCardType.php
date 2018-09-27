<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 13:45
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\KoornbeursCard;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class KoornbeursCardType extends ModelType
{

    public $modelClass = KoornbeursCard::class;

    public $name = 'KoornbeursCard';

    public $description = "The `KoornbeursCard`-type models the unique card used in the Koornbeurs to pay at the bar,
                           open doors, etc.";

    public function fields()
    {
        return [
            'person' => [
                'description' => 'The `Person` that is the current owner of this `KoornbeursCard`.',
                'type' => GraphQL::type('Person'),
                'alias' => 'owner',
                'importance' => 200,
            ],
            'ref' => [
                'description' => 'The (unique) reference of the card, printed on the card itself.',
                'type' => GraphQL::type('String'),
                'importance' => 250,
            ],
            'version' => [
                'description' => 'The version of the card.',
                'type' => GraphQL::type('String'),
                'importance' => 249
            ],
            'activatedAt' => [
                'description' => 'The moment on which this `KoornbeursCard` was activated.',
                'type' => GraphQL::type('DateTime'),
                'importance' => 5,
            ],
            'deactivatedAt' => [
                'description' => 'The moment on which this `KoornbeursCard` was deactivated.',
                'type' => GraphQL::type('DateTime'),
                'importance' => 4,
            ],
            'isActive' => [
                'description' => 'Whether or not this `KoornbeursCard` is/was/will be active at a given moment.',
                'type' => GraphQL::type('Boolean'),
                'args' => [
                    'at' => [
                        'description' => 'The moment for which you what to check if this `KoornbeursCard` was active.
                                          If this argument is omitted or set to `null`, the current moment will be
                                          used.',
                        'type' => GraphQL::type('DateTime')
                    ]
                ],
                'importance' => 10,
            ],
        ];
    }

    public function filters()
    {
        return [
            'version' => [
                'type' => GraphQL::type('String'),
                'description' => 'Filters the KoornbeursCards with the (identical) provided version.'
            ],

            'active' => [
                'type' => GraphQL::type('Boolean'),
                'description' => 'Filters the KoornbeursCards that are active if the value is `true`, or inactive if the value is `false`.',
            ],

            'activeAt' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were/are active at the given moment.',
            ],

            'inactiveAt' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were/are inactive at the given moment.'
            ],

            'activatedBefore' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCarts that were activated before the provided moment.'
            ],

            'activatedAfter' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were activated after the provided moment.'
            ],

            'deactivatedBefore' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were deactivated before the provided moment.'
            ],

            'deactivatedAfter' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were deactivated after the provided moment.'
            ]
        ];
    }
}