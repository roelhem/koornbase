<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 11:00
 */

namespace App\GraphQL\Types;


use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\PersonEmailAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PersonEmailAddressType extends GraphQLType
{

    protected $attributes = [
        'name' => 'PersonEmailAddress',
        'model' => PersonEmailAddress::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model'),
            GraphQL::type('OwnedByPerson'),
            GraphQL::type('PersonContactEntry'),
        ];
    }

    /** @inheritdoc */
    public function fields()
    {

        $ownedByPersonInterface = GraphQL::type('OwnedByPerson');
        $personContactEntryInterface = GraphQL::type('PersonContactEntry');

        return [
            GraphQL::type('Model')->getField('id'),
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),

            $personContactEntryInterface->getField('person_id'),
            $personContactEntryInterface->getField('person'),
            $personContactEntryInterface->getField('index'),
            $personContactEntryInterface->getField('label'),

            'email_address' => [
                'type' => Type::string(),

                'created_at' => CreatedAtField::class,
                'created_by' => CreatedByField::class,
                'creator'    => CreatorField::class,
                'updated_at' => UpdatedAtField::class,
                'updated_by' => UpdatedByField::class,
                'editor'     => EditorField::class,
            ]
        ];
    }

}