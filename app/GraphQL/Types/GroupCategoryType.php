<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:49
 */

namespace App\GraphQL\Types;


use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\DeletedAtField;
use App\GraphQL\Fields\Stamps\DeletedByField;
use App\GraphQL\Fields\Stamps\DestroyerField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\GroupCategory;
use GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

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
            GraphQL::type('Model')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        return [
            GraphQL::type('Model')->getField('id'),
            GraphQL::type('Sluggable')->getField('slug'),

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