<?php

namespace App\GraphQL\Types;

use App\GraphQL\Fields\DescriptionField;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\NameField;
use App\GraphQL\Fields\NameShortField;
use App\GraphQL\Fields\SlugField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\DeletedAtField;
use App\GraphQL\Fields\Stamps\DeletedByField;
use App\GraphQL\Fields\Stamps\DestroyerField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\OAuth\App;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AppType extends GraphQLType
{
    protected $attributes = [
        'name' => 'App',
        'description' => 'An external app.',
        'model' => App::class,
    ];

    public function fields()
    {
        return [
            'id' => IdField::class,
            'slug' => SlugField::class,
            'name' => NameField::class,
            'name_short' => NameShortField::class,
            'description' => DescriptionField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator' => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor' => EditorField::class,
            'deleted_at' => DeletedAtField::class,
            'deleted_by' => DeletedByField::class,
            'destroyer' => DestroyerField::class,
        ];
    }
}