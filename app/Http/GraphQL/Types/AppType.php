<?php

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Fields\DescriptionField;
use App\Http\GraphQL\Fields\IdField;
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