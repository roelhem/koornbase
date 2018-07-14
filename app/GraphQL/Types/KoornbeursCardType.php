<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:50
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
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
            GraphQL::type('Model')->getField('id'),
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),
            'ref' => [
                'type' => Type::string(),
            ],
            'version' => [
                'type' => Type::string(),
            ],
            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
        ];
    }

}