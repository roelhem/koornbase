<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 07:46
 */

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Fields\IdField;
use App\Http\GraphQL\Fields\Stamps\CreatedAtField;
use App\Http\GraphQL\Fields\Stamps\UpdatedAtField;
use App\Logs\LogGraphQLOperation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class LogGraphQLOperationType extends GraphQLType
{

    protected $attributes = [
        'name' => 'LogGraphQLOperation',
        'description' => 'A log entry of a GraphQL-operation that someone tried to execute.',
        'model' => LogGraphQLOperation::class,
    ];

    public function interfaces()
    {
        return [
            \GraphQL::type('Model')
        ];
    }

    public function fields()
    {
        return [
            'id' => IdField::class,
            'schema' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The GraphQL-schema on which the operation was executed.',
            ],
            'type' => [
                'type' => Type::nonNull(\GraphQL::type('GraphQLOperationType')),
                'description' => 'The type of operation of the GraphQL-operation.'
            ],
            'operation_name' => [
                'type' => Type::string(),
                'description' => 'The name of the operation.',
            ],
            'query' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The `query` that defines the operation',
            ],
            'variables' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The variables that were used to execute the operation. Gives a JSON-formatted string.',
                'resolve' => function(LogGraphQLOperation $root) {
                    return json_encode($root->variables);
                }
            ],
            'user_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the `User` that tried to execute the operation.',
            ],
            'user' => [
                'type' => \GraphQL::type('User'),
                'description' => 'The `User` that tried to execute the operation.',
            ],
            'client_id' => [
                'type' => Type::id(),
                'description' => 'the `ID` of the `OAuthClient` that was used to access the API to execute the operation.',
            ],
            'client' => [
                'type' => \GraphQL::type('OAuthClient'),
                'description' => 'The `OAuthClient` that was was used to access the API to execute the operation.',
            ],
            'access_token_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the `OAuthToken` that was used to access the API to execute the operation.',
            ],
            'token' => [
                'type' => \GraphQL::type('OAuthToken'),
                'description' => 'The `OAuthToken` that was used to access the API to execute the operation.',
            ],
            'requested_at' => [
                'type' => Type::nonNull(\GraphQL::type('DateTime')),
                'description' => 'The `DateTime` on which this operation was executed.',
            ],
            'created_at' => CreatedAtField::class,
            'updated_at' => UpdatedAtField::class,
        ];
    }

}