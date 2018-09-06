<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 11:00
 */

namespace App\Http\GraphQL\Types;


use App\Http\GraphQL\Fields\Authorization\ViewableField;
use App\Http\GraphQL\Fields\IdField;
use App\Http\GraphQL\Fields\Relations\PersonField;
use App\Http\GraphQL\Fields\Relations\PersonIdField;
use App\Http\GraphQL\Fields\RemarksField;
use App\Http\GraphQL\Fields\Stamps\CreatedAtField;
use App\Http\GraphQL\Fields\Stamps\CreatedByField;
use App\Http\GraphQL\Fields\Stamps\CreatorField;
use App\Http\GraphQL\Fields\Stamps\EditorField;
use App\Http\GraphQL\Fields\Stamps\UpdatedAtField;
use App\Http\GraphQL\Fields\Stamps\UpdatedByField;
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
            GraphQL::type('StampedModel'),
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
            'id' => IdField::class,
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),

            'person_id' => PersonIdField::class,
            'person' => PersonField::class,
            $personContactEntryInterface->getField('index'),
            $personContactEntryInterface->getField('label'),

            'email_address' => [
                'type' => \GraphQL::type('Email'),
                'description' => 'The value of the e-mail address.'
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