<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 16:42
 */

namespace App\Http\GraphQLNew;



use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Resolvers\ModelByIdResolver;
use Roelhem\GraphQL\Resolvers\ModelListResolver;
use Roelhem\GraphQL\Types\QueryType;

class Query extends QueryType
{

    protected $modelByIdResolver;
    protected $modelListResolver;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->modelByIdResolver = new ModelByIdResolver();
        $this->modelListResolver = new ModelListResolver();
    }

    protected $modelByIdFields = [
        'Person',
        'KoornbeursCard',
        'CertificateCategory',
        'Group',
        'GroupCategory',
        'User'
    ];

    protected $modelListFields = [
        'persons' => 'Person',
        'KoornbeursCard',
        'CertificateCategory',
        'Group',
        'GroupCategory',
        'User'
    ];


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
        ];
    }

}