<?php

namespace App\GraphQL\Types;

use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
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