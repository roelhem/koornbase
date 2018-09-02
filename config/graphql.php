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
    'middleware' => ['auth:api'],

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
    //              'users' => 'App\Http\GraphQL\Query\UsersQuery'
    //          ],
    //          'mutation' => [
    //
    //          ]
    //      ],
    //      'user' => [
    //          'query' => [
    //              'profile' => 'App\Http\GraphQL\Query\ProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //      'user/me' => [
    //          'query' => [
    //              'profile' => 'App\Http\GraphQL\Query\MyProfileQuery'
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

                // MODEL QUERIES
                'certificateCategories' => \App\Http\GraphQL\Queries\CertificateCategoriesQuery::class,
                'certificateCategory' => \App\Http\GraphQL\Queries\CertificateCategoryQuery::class,

                'certificates' => \App\Http\GraphQL\Queries\CertificatesQuery::class,
                'certificate' => \App\Http\GraphQL\Queries\CertificateQuery::class,

                'debtors' => \App\Http\GraphQL\Queries\DebtorsQuery::class,
                'debtor' => \App\Http\GraphQL\Queries\DebtorQuery::class,

                'groupCategories' => \App\Http\GraphQL\Queries\GroupCategoriesQuery::class,
                'groupCategory' => \App\Http\GraphQL\Queries\GroupCategoryQuery::class,

                'groupEmailAddresses' => \App\Http\GraphQL\Queries\GroupEmailAddressesQuery::class,
                'groupEmailAddress' => \App\Http\GraphQL\Queries\GroupEmailAddressQuery::class,

                'groups' => \App\Http\GraphQL\Queries\GroupsQuery::class,
                'group' => \App\Http\GraphQL\Queries\GroupQuery::class,

                'koornbeursCards' => \App\Http\GraphQL\Queries\KoornbeursCardsQuery::class,
                'koornbeursCard' => \App\Http\GraphQL\Queries\KoornbeursCardQuery::class,

                'memberships' => \App\Http\GraphQL\Queries\MembershipsQuery::class,
                'membership' => \App\Http\GraphQL\Queries\MembershipQuery::class,

                'persons' => \App\Http\GraphQL\Queries\PersonsQuery::class,
                'person' => \App\Http\GraphQL\Queries\PersonQuery::class,

                'personPhoneNumbers' => \App\Http\GraphQL\Queries\PersonPhoneNumbersQuery::class,
                'personPhoneNumber' => \App\Http\GraphQL\Queries\PersonPhoneNumberQuery::class,

                'personAddresses' => \App\Http\GraphQL\Queries\PersonAddressesQuery::class,
                'personAddress' => \App\Http\GraphQL\Queries\PersonAddressQuery::class,

                'personEmailAddresses' => \App\Http\GraphQL\Queries\PersonEmailAddressesQuery::class,
                'personEmailAddress' => \App\Http\GraphQL\Queries\PersonEmailAddressQuery::class,

                'users' => \App\Http\GraphQL\Queries\UsersQuery::class,
                'user' => \App\Http\GraphQL\Queries\UserQuery::class,

                'userAccounts' => \App\Http\GraphQL\Queries\UserAccountsQuery::class,
                'userAccount' => \App\Http\GraphQL\Queries\UserAccountQuery::class,

                'apps' => \App\Http\GraphQL\Queries\AppsQuery::class,
                'app' => \App\Http\GraphQL\Queries\AppQuery::class,

                'oauthClients' => \App\Http\GraphQL\Queries\OAuthClientsQuery::class,
                'oauthClient' => \App\Http\GraphQL\Queries\OAuthClientQuery::class,

                'rbacGraph' => \Roelhem\RbacGraph\Http\GraphQL\Queries\RbacGraphQuery::class,

                'hello' => \App\Http\GraphQL\Queries\HelloQuery::class,
                // QUERIES ABOUT THE CURRENT SESSION
                'me' => \App\Http\GraphQL\Queries\MeQuery::class
            ],
            'mutation' => [
                // PERSONS
                // Person
                \App\Http\GraphQL\Mutations\Crud\Create\CreatePersonMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdatePersonMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeletePersonMutation::class,
                \App\Http\GraphQL\Mutations\Crud\RestorePersonMutation::class,
                // PersonAddress
                \App\Http\GraphQL\Mutations\Crud\Create\CreatePersonAddressMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeletePersonAddressMutation::class,
                // PersonEmailAddress
                \App\Http\GraphQL\Mutations\Crud\Create\CreatePersonEmailAddressMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdatePersonPhoneNumberMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeletePersonEmailAddressMutation::class,
                // PersonPhoneNumber
                \App\Http\GraphQL\Mutations\Crud\Create\CreatePersonPhoneNumberMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdatePersonPhoneNumberMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeletePersonPhoneNumberMutation::class,

                // PersonGroupConnection
                \App\Http\GraphQL\Mutations\Crud\Create\CreatePersonGroupConnectionMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeletePersonGroupConnectionMutation::class,

                // Memberships
                \App\Http\GraphQL\Mutations\Crud\Create\CreateMembershipMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdateMembershipMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeleteMembershipMutation::class,
                \App\Http\GraphQL\Mutations\NewMembershipApplicationMutation::class,
                \App\Http\GraphQL\Mutations\StartMembershipMutation::class,
                \App\Http\GraphQL\Mutations\StopMembershipMutation::class,

                // GROUPS
                // Group
                \App\Http\GraphQL\Mutations\Crud\Create\CreateGroupMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdateGroupMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeleteGroupMutation::class,
                // GroupCategory
                \App\Http\GraphQL\Mutations\Crud\Create\CreateGroupCategoryMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdateGroupCategoryMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeleteGroupCategoryMutation::class,
                // GroupEmailAddress
                \App\Http\GraphQL\Mutations\Crud\Create\CreateGroupEmailAddressMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdateGroupEmailAddressMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeleteGroupEmailAddressMutation::class,

                // CERTIFICATES
                // Certificate
                \App\Http\GraphQL\Mutations\Crud\Create\CreateCertificateMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdateCertificateMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeleteCertificateMutation::class,
                // CertificateCategory
                \App\Http\GraphQL\Mutations\Crud\Create\CreateCertificateCategoryMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdateCertificateCategoryMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Delete\DeleteCertificateCategoryMutation::class,

                // APPS & OAUTH
                // App
                \App\Http\GraphQL\Mutations\Crud\Create\CreateAppMutation::class,
                // OAuthClient
                \App\Http\GraphQL\Mutations\Crud\Create\CreateOAuthClientMutation::class,
                \App\Http\GraphQL\Mutations\Crud\Update\UpdateOAuthClientMutation::class,
                \App\Http\GraphQL\Mutations\RevokeOAuthClientMutation::class,
                // Request PersonalAccessToken
                \App\Http\GraphQL\Mutations\RequestPersonalAccessTokenMutation::class,
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
    //     'user' => 'App\Http\GraphQL\Type\UserType'
    // ]
    //
    'types' => [
        'Model' => \App\Http\GraphQL\Interfaces\ModelInterface::class,
        'OwnedByPerson' => \App\Http\GraphQL\Interfaces\OwnedByPersonInterface::class,
        'PersonContactEntry' => \App\Http\GraphQL\Interfaces\PersonContactEntryInterface::class,
        'OAuthClient' => \App\Http\GraphQL\Interfaces\OAuthClientInterface::class,

        'PhoneNumberFormat' => \App\Http\GraphQL\Enums\PhoneNumberFormatEnum::class,
        'PhoneNumberType' => \App\Http\GraphQL\Enums\PhoneNumberTypeEnum::class,

        'Certificate' => \App\Http\GraphQL\Types\CertificateType::class,
        'CertificateCategory' => \App\Http\GraphQL\Types\CertificateCategoryType::class,
        'Debtor' => \App\Http\GraphQL\Types\DebtorType::class,
        'Group' => \App\Http\GraphQL\Types\GroupType::class,
        'GroupCategory' => \App\Http\GraphQL\Types\GroupCategoryType::class,
        'GroupEmailAddress' => \App\Http\GraphQL\Types\GroupEmailAddressType::class,
        'KoornbeursCard' => \App\Http\GraphQL\Types\KoornbeursCardType::class,
        'Membership' => \App\Http\GraphQL\Types\MembershipType::class,
        'Person' => \App\Http\GraphQL\Types\PersonType::class,
        'PersonAddress' => \App\Http\GraphQL\Types\PersonAddressType::class,
        'PersonEmailAddress' => \App\Http\GraphQL\Types\PersonEmailAddressType::class,
        'PersonPhoneNumber' => \App\Http\GraphQL\Types\PersonPhoneNumberType::class,
        'PersonGroupConnection' => \App\Http\GraphQL\Types\PersonGroupConnectionType::class,
        'User' => \App\Http\GraphQL\Types\UserType::class,
        'UserAccount' => \App\Http\GraphQL\Types\UserAccountType::class,

        'App' => \App\Http\GraphQL\Types\AppType::class,

        'OAuthPersonalClient' => \App\Http\GraphQL\Types\OAuthPersonalClientType::class,
        'OAuthPasswordClient' => \App\Http\GraphQL\Types\OAuthPasswordClientType::class,
        'OAuthCredentialsClient' => \App\Http\GraphQL\Types\OAuthCredentialsClientType::class,
        'OAuthAuthCodeClient' => \App\Http\GraphQL\Types\OAuthAuthCodeClientType::class,

        'OAuthPersonalAccessClient' => \App\Http\GraphQL\Types\OAuthPersonalAccessClientType::class,
        'OAuthPersonalAccessTokenResult' => \App\Http\GraphQL\Types\OAuthPersonalAccessTokenResultType::class,

        'OAuthAuthCode' => \App\Http\GraphQL\Types\OAuthAuthCodeType::class,
        'OAuthToken' => \App\Http\GraphQL\Types\OAuthTokenType::class,

        'Avatar' => \App\Http\GraphQL\Types\AvatarType::class,

        'HtmlAttributes' => \App\Http\GraphQL\Types\Inputs\HtmlAttributesType::class,

        'Date' => \App\Http\GraphQL\Types\Scalars\DateType::class,
        'DateTime' => \App\Http\GraphQL\Types\Scalars\DateTimeType::class,
        'CountryCode' => \App\Http\GraphQL\Types\Scalars\CountryCodeType::class,
        \App\Http\GraphQL\Types\Scalars\EmailType::class,

        //'RbacNode' => \Roelhem\RbacGraph\Http\GraphQL\Types\RbacNodeType::class
    ],
    
    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    'error_formatter' => ['\App\Http\GraphQL\Exceptions\ErrorFormatter', 'format'],

    // You can set the key, which will be used to retrieve the dynamic variables
    'params_key'    => 'variables',

    'output_formats' => [
        'date' => 'Y-m-d',
        'datetime' => 'Y-m-d H:i:s',
    ],

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
