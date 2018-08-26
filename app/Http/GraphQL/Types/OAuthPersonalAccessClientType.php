<?php

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Fields\IdField;
use App\Http\GraphQL\Fields\Stamps\CreatedAtField;
use App\Http\GraphQL\Fields\Stamps\UpdatedAtField;
use App\OAuth\PersonalAccessClient;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class OAuthPersonalAccessClientType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OAuthPersonalAccessClient',
        'model' => PersonalAccessClient::class,
    ];

    public function fields()
    {
        return [
            'id' => IdField::class,
            'client_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the parent OAuthPersonalClient.',
            ],
            'client' => [
                'type' => \GraphQL::type('OAuthPersonalClient'),
                'description' => 'The parent OAuthPersonalClient.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure()
            ],
            'created_at' => CreatedAtField::class,
            'updated_at' => UpdatedAtField::class
        ];
    }
}