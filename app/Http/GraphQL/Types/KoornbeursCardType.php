<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:50
 */

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Fields\Authorization\ViewableField;
use App\Http\GraphQL\Fields\IdField;
use App\Http\GraphQL\Fields\RemarksField;
use App\Http\GraphQL\Fields\Stamps\CreatedAtField;
use App\Http\GraphQL\Fields\Stamps\CreatedByField;
use App\Http\GraphQL\Fields\Stamps\CreatorField;
use App\Http\GraphQL\Fields\Stamps\EditorField;
use App\Http\GraphQL\Fields\Stamps\UpdatedAtField;
use App\Http\GraphQL\Fields\Stamps\UpdatedByField;
use App\KoornbeursCard;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;


class KoornbeursCardType extends GraphQLType
{

    protected $attributes = [
        'name' => 'KoornbeursCard',
        'model' => KoornbeursCard::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model'),
            GraphQL::type('OwnedByPerson')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        $ownedByPersonInterface = GraphQL::type('OwnedByPerson');

        return [
            'id' => IdField::class,
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),

            'ref' => [
                'type' => Type::string(),
                'description' => 'The unique reference on the card itself. Can be used in combination with `version` to uniquely identify a card.'
            ],
            'version' => [
                'type' => Type::string(),
                'description' => 'The version of the KoornbeursCard. Can be used in combination with `ref` to uniquely identify a card.'
            ],

            'activated_at' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'The moment on which this KoornbeursCard was/is activated.',
            ],
            'deactivated_at' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'The moment on which this KoornbeursCard was/is deactivated.'
            ],

            'is_active' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Returns if this KoornbeursCard is active at a specific moment.',
                'args' => [
                    'at' => [
                        'type' => GraphQL::type('DateTime'),
                        'description' => 'The moment to check.'
                    ]
                ],
                'resolve' => function(KoornbeursCard $card, $args) {
                    $at = array_get($args,'at',null);
                    return $card->isActive($at);
                },
                'selectable' => false,
                'always' => ['activated_at','deactivated_at']
            ],

            'remarks' => RemarksField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,

            'viewable' => ViewableField::class
        ];
    }

}