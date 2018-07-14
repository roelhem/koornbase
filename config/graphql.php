<?php

return [

    // The prefix for routes
    'prefix' => 'graphql',

    // The routes to make GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Route
    //
    // Example:
    //
    // Same route for both query and mutation
    //
    // 'routes' => 'path/to/query/{graphql_schema?}',
    //
    // or define each route
    //
    // 'routes' => [
    //     'query' => 'query/{graphql_schema?}',
    //     'mutation' => 'mutation/{graphql_schema?}',
    // ]
    //
    'routes' => '{schema?}',

    // The controller to use in GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Controller and method
    //
    // Example:
    //
    // 'controllers' => [
    //     'query' => '\Rebing\GraphQL\GraphQLController@query',
    //     'mutation' => '\Rebing\GraphQL\GraphQLController@mutation'
    // ]
    //
    'controllers' => \Rebing\GraphQL\GraphQLController::class . '@query',

    // Any middleware for the graphql route group
    'middleware' => [],

    // The name of the default schema used when no argument is provided
    // to GraphQL::schema() or when the route is used without the graphql_schema
    // parameter.
    'default_schema' => 'default',

    // The schemas for query and/or mutation. It expects an array of schemas to provide
    // both the 'query' fields and the 'mutation' fields.
    //
    // You can also provide a middleware that will only apply to the given schema
    //
    // Example:
    //
    //  'schema' => 'default',
    //
    //  'schemas' => [
    //      'default' => [
    //          'query' => [
    //              'users' => 'App\GraphQL\Query\UsersQuery'
    //          ],
    //          'mutation' => [
    //
    //          ]
    //      ],
    //      'user' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\ProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //      'user/me' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\MyProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //  ]
    //
    'schemas' => [
        'default' => [
            'query' => [
                'persons' => \App\GraphQL\Queries\PersonsQuery::class,
                'personPhoneNumbers' => \App\GraphQL\Queries\PersonPhoneNumbersQuery::class,
                'personAddresses' => \App\GraphQL\Queries\PersonAddressesQuery::class,
                'users' => \App\GraphQL\Queries\UsersQuery::class,
            ],
            'mutation' => [
            ],
            'middleware' => []
        ],
        'rbac' => []
        /*'rbac' => [
            'query' => [
                'nodes' => \Roelhem\RbacGraph\Http\GraphQL\Queries\RbacNodesQuery::class
            ]
        ]*/
    ],
    
    // The types available in the application. You can then access it from the
    // facade like this: GraphQL::type('user')
    //
    // Example:
    //
    // 'types' => [
    //     'user' => 'App\GraphQL\Type\UserType'
    // ]
    //
    'types' => [
        'Model' => \App\GraphQL\Interfaces\ModelInterface::class,
        'Sluggable' => \App\GraphQL\Interfaces\SluggableInterface::class,
        'OwnedByPerson' => \App\GraphQL\Interfaces\OwnedByPersonInterface::class,
        'PersonContactEntry' => \App\GraphQL\Interfaces\PersonContactEntryInterface::class,
        'BelongsToCountry' => \App\GraphQL\Interfaces\BelongsToCountryInterface::class,

        'PhoneNumberFormat' => \App\GraphQL\Enums\PhoneNumberFormatEnum::class,
        'PhoneNumberType' => \App\GraphQL\Enums\PhoneNumberTypeEnum::class,

        'Certificate' => \App\GraphQL\Types\CertificateType::class,
        'CertificateCategory' => \App\GraphQL\Types\CertificateCategoryType::class,
        'Debtor' => \App\GraphQL\Types\DebtorType::class,
        'Group' => \App\GraphQL\Types\GroupType::class,
        'GroupCategory' => \App\GraphQL\Types\GroupCategoryType::class,
        'GroupEmailAddress' => \App\GraphQL\Types\GroupEmailAddressType::class,
        'KoornbeursCard' => \App\GraphQL\Types\KoornbeursCardType::class,
        'Membership' => \App\GraphQL\Types\MembershipType::class,
        'Person' => \App\GraphQL\Types\PersonType::class,
        'PersonAddress' => \App\GraphQL\Types\PersonAddressType::class,
        'PersonEmailAddress' => \App\GraphQL\Types\PersonEmailAddressType::class,
        'PersonPhoneNumber' => \App\GraphQL\Types\PersonPhoneNumberType::class,
        'User' => \App\GraphQL\Types\UserType::class,
        'UserAccount' => \App\GraphQL\Types\UserAccountType::class,

        'HtmlAttributes' => \App\GraphQL\Types\Inputs\HtmlAttributesType::class,

        'Date' => \App\GraphQL\Types\Scalars\DateType::class,
        'DateTime' => \App\GraphQL\Types\Scalars\DateTimeType::class,

        //'RbacNode' => \Roelhem\RbacGraph\Http\GraphQL\Types\RbacNodeType::class
    ],
    
    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    // You can set the key, which will be used to retrieve the dynamic variables
    'params_key'    => 'params',

    /*
     * Options to limit the query complexity and depth. See the doc
     * @ https://github.com/webonyx/graphql-php#security
     * for details. Disabled by default.
     */
    'security' => [
        'query_max_complexity' => null,
        'query_max_depth' => null,
        'disable_introspection' => false
    ]
];
