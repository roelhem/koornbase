<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 16:42
 */

namespace App\Http\GraphQLNew;



use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Fields\ModelByIdField;
use Roelhem\GraphQL\Resolvers\QueryModelByIdResolver;
use Roelhem\GraphQL\Resolvers\QueryModelListResolver;
use Roelhem\GraphQL\Types\QueryType;

class Query extends QueryType
{

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function connections()
    {
        return [
            'persons' => 'Person',
            'KoornbeursCard',
            'CertificateCategory',
            'Group',
            'GroupCategory',
            'User'
        ];
    }


    public function fields()
    {
        return [
            'hello' => [
                'type' => GraphQL::type('String!'),
                'resolve' => function() { return 'Hello World!'; },
            ],

            'names' => [
                'type' => GraphQL::type('[String]'),
                'resolve' => function() { return GraphQL::typeLoader()->repository()->getNames(); },
            ],

            new ModelByIdField(['type' => GraphQL::type('Person')]),
            new ModelByIdField(['type' => GraphQL::type('KoornbeursCard')]),
            new ModelByIdField(['type' => GraphQL::type('CertificateCategory')]),
            new ModelByIdField(['type' => GraphQL::type('Group')]),
            new ModelByIdField(['type' => GraphQL::type('GroupCategory')]),
            new ModelByIdField(['type' => GraphQL::type('User')]),
        ];
    }

}