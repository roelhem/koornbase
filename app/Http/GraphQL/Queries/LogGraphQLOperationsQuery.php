<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 08:11
 */

namespace App\Http\GraphQL\Queries;


use App\Logs\LogGraphQLOperation;
use GraphQL\Type\Definition\Type;

class LogGraphQLOperationsQuery extends ModelListQuery
{

    protected $modelClass = LogGraphQLOperation::class;

    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [

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

        ]);
    }

}