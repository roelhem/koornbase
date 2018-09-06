<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:22
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class LogGraphQLOperationFilterType extends FilterType
{

    public function filters()
    {
        return [
            'schema' => [
                'description' => 'Filters only the operations that were executed on the schema with the given name.',
                'type' => Type::string(),
            ],

            'type' => [
                'description' => 'Filters only the operation that have a certain type.',
                'type' => \GraphQL::type('GraphQLOperationType'),
            ],

            'operationName' => [
                'description' => 'Filters only the operations that have the given operation name.',
                'type' => Type::string(),
            ],

            'userId' => [
                'description' => 'Filters only the operations that were executed by the `User` that has the same `ID`.',
                'type' => Type::id(),
            ],
            'clientId' => [
                'description' => 'Filters only the operations that were executed using the `OAuthClient` with the same `ID`.',
                'type' => Type::id(),
            ],
            'tokenId' => [
                'description' => 'Filters only the operations that were executed using the `OAuthToken` with the same `ID`.',
                'type' => Type::id(),
            ],
            'before' => [
                'description' => 'Filters only the operations that were executed before the provided `DateTime`.',
                'type' => \GraphQL::type('DateTime'),
            ],
            'after' => [
                'description' => 'Filters only the operations that were executed after the provided `DateTime`.',
                'type' => \GraphQL::type('DateTime'),
            ]
        ];
    }

}