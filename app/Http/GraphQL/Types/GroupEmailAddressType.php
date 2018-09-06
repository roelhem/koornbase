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
use App\GroupEmailAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class GroupEmailAddressType extends GraphQLType
{

    protected $attributes = [
        'name' => 'GroupEmailAddress',
        'model' => GroupEmailAddress::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model'),
            GraphQL::type('StampedModel'),
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        return [
            'id' => IdField::class,

            'group_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the Group where this GroupEmailAddress belongs to.'
            ],
            'group' => [
                'type' => GraphQL::type('Group'),
                'description' => 'The Group where this GroupEmailAddress belongs to.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure()
            ],

            'email_address' => [
                'type' => Type::nonNull(\GraphQL::type('Email')),
                'description' => 'The e-mail address.',
            ],

            'remarks' => RemarksField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,

            'viewable' => ViewableField::class,
        ];
    }


}