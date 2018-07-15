<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:49
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\DescriptionField;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\IsRequiredField;
use App\GraphQL\Fields\NameField;
use App\GraphQL\Fields\NameShortField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\DeletedAtField;
use App\GraphQL\Fields\Stamps\DeletedByField;
use App\GraphQL\Fields\Stamps\DestroyerField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\Group;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

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
            GraphQL::type('Model')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        return [
            'id' => IdField::class,
            GraphQL::type('Sluggable')->getField('slug'),

            'category_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The `ID` of the GroupCategory where this Group belongs to.',
            ],
            'category' => [
                'type' => GraphQL::type('GroupCategory'),
                'description' => 'The GroupCategory where this Group belongs to.',
            ],

            'name'        => NameField::class,
            'name_short'  => NameShortField::class,
            'description' => DescriptionField::class,

            'member_name' => [
                'type' => Type::string(),
                'description' => 'A string that contains a name for a person that is a member of this Group.'
            ],

            'persons' => [
                'type' => Type::listOf(GraphQL::type('Person')),
                'description' => 'All the persons that are in this group.'
            ],

            'is_required' => IsRequiredField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
            'deleted_at' => DeletedAtField::class,
            'deleted_by' => DeletedByField::class,
            'destroyer'  => DestroyerField::class

        ];
    }

}