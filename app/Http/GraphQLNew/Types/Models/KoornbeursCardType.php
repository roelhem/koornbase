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
            ],
            'ref' => [
                'description' => 'The (unique) reference of the card, printed on the card itself.',
                'type' => GraphQL::type('String!'),
            ],
            'version' => [
                'description' => 'The version of the card.',
                'type' => GraphQL::type('String'),
            ],
            'activatedAt' => [
                'description' => 'The moment on which this `KoornbeursCard` was activated.',
                'type' => GraphQL::type('DateTime'),
            ],
            'deactivatedAt' => [
                'description' => 'The moment on which this `KoornbeursCard` was deactivated.',
                'type' => GraphQL::type('DateTime'),
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
                ]
            ],
        ];
    }
}