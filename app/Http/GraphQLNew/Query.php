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
use Roelhem\GraphQL\Resolvers\ResolveContext;
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
            'User',
            'oauthClients' => 'OAuthClient'
        ];
    }

    public function fields()
    {
        return [

            'me' => [
                'description' => "Returns the `User` of the current session. Will return `null` if there is no logged user.",
                'type' => GraphQL::type('User'),
                'resolve' => function($source, $args, ResolveContext $context) {
                    return $context->user();
                },
                'importance' => 250
            ],


            new ModelByIdField(['type' => GraphQL::type('Person')]),
            new ModelByIdField(['type' => GraphQL::type('KoornbeursCard')]),
            new ModelByIdField(['type' => GraphQL::type('CertificateCategory')]),
            new ModelByIdField(['type' => GraphQL::type('Group')]),
            new ModelByIdField(['type' => GraphQL::type('GroupCategory')]),
            new ModelByIdField(['type' => GraphQL::type('User')]),

            new ModelByIdField([
                'name' => 'oauthClient',
                'type' => GraphQL::type('OAuthClient')
            ]),
        ];
    }

}