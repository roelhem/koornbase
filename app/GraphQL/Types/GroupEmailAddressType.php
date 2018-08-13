<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:50
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Authorization\ViewableField;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\RemarksField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
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
            GraphQL::type('Model')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        return [
            'id' => IdField::class,

            'group_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The `ID` of the Group where this GroupEmailAddress belongs to.'
            ],
            'group' => [
                'type' => GraphQL::type('Group'),
                'description' => 'The Group where this GroupEmailAddress belongs to.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure()
            ],

            'email_address' => [
                'type' => Type::nonNull(Type::string()),
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