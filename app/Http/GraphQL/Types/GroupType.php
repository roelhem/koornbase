<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:49
 */

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Fields\Authorization\ViewableField;
use App\Http\GraphQL\Fields\DescriptionField;
use App\Http\GraphQL\Fields\IdField;
use App\Http\GraphQL\Fields\IsRequiredField;
use App\Http\GraphQL\Fields\NameField;
use App\Http\GraphQL\Fields\NameShortField;
use App\Http\GraphQL\Fields\SlugField;
use App\Http\GraphQL\Fields\Stamps\CreatedAtField;
use App\Http\GraphQL\Fields\Stamps\CreatedByField;
use App\Http\GraphQL\Fields\Stamps\CreatorField;
use App\Http\GraphQL\Fields\Stamps\DeletedAtField;
use App\Http\GraphQL\Fields\Stamps\DeletedByField;
use App\Http\GraphQL\Fields\Stamps\DestroyerField;
use App\Http\GraphQL\Fields\Stamps\EditorField;
use App\Http\GraphQL\Fields\Stamps\UpdatedAtField;
use App\Http\GraphQL\Fields\Stamps\UpdatedByField;
use App\Group;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class GroupType extends GraphQLType
{

    protected $attributes = [
        'name' => 'Group',
        'model' => Group::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model'),
            GraphQL::type('StampedModel'),
            GraphQL::type('SoftDeleteModel')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        return [
            'id' => IdField::class,
            'slug' => SlugField::class,

            'category_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The `ID` of the GroupCategory where this Group belongs to.',
            ],
            'category' => [
                'type' => GraphQL::type('GroupCategory'),
                'description' => 'The GroupCategory where this Group belongs to.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure()
            ],

            'name'        => NameField::class,
            'name_short'  => NameShortField::class,
            'description' => DescriptionField::class,

            'member_name' => [
                'type' => Type::string(),
                'description' => 'A string that contains a name for a person that is a member of this Group.'
            ],

            \GraphQL::builder()->relationField([
                'name' => 'persons',
                'type' => 'Person',
                'description' => 'A list of the `Person`s that are currently members of this group.',
            ]),

            \GraphQL::builder()->relationField([
                'name' => 'emailAddresses',
                'type' => 'GroupEmailAddress',
                'description' => 'All the email addresses that are registered for this `Group`.',
            ]),

            'is_required' => IsRequiredField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
            'deleted_at' => DeletedAtField::class,
            'deleted_by' => DeletedByField::class,
            'destroyer'  => DestroyerField::class,


            'viewable' => ViewableField::class

        ];
    }

}