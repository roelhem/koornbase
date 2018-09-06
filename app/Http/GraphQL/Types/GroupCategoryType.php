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
use App\GroupCategory;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class GroupCategoryType extends GraphQLType
{

    protected $attributes = [
        'name' => 'GroupCategory',
        'model' => GroupCategory::class
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

            'name'        => NameField::class,
            'name_short'  => NameShortField::class,
            'description' => DescriptionField::class,

            'style' => [
                'type' => Type::string(),
                'description' => 'The name of the style in which a Group of this GroupCategory should be displayed.'
            ],

            \GraphQL::builder()->relationField([
                'name' => 'groups',
                'type' => 'Group',
                'description' => 'A list of all the groups that belong to this category.',
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


            'viewable' => ViewableField::class,
        ];
    }

}