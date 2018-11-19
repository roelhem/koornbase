<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 12:37
 */

namespace App\Http\GraphQL\Interfaces;


use App\OAuth\Client;
use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\ResolveInfo;
use Roelhem\GraphQL\Contracts\ModelTypeContract;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\Filters\FilterInputType;
use Roelhem\GraphQL\Types\InterfaceType;
use Roelhem\GraphQL\Types\OrderBy\OrderByInputType;
use Roelhem\GraphQL\Types\Traits\HasConnectionFields;

class OAuthClientInterface extends InterfaceType implements ModelTypeContract
{

    use HasConnectionFields;

    public $name = 'OAuthClient';

    public $description = "The `OAuthClient`-type interface is an interface for models that represents client-applications
        of the *KoornBase*. If an application wants to use the API's of the *KoornBase*, it must have
        a `OAuthClient` that is known by the server.
        \n\nThe model-types that implement this interface are all OAuthClient models, but each type
        has a own way of authorizing users to the *KoornBase* using the client.";

    /**
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    protected function fields()
    {
        return [
            'name' => [
                'description' => "The name of the `OAuthClient`. Helps to identify the function of the client.",
                'type' => GraphQL::type('String'),
                'importance' => 254,
            ],
            'user' => [
                'description' => "The `User` who manages the `OAuthClient`.",
                'type' => GraphQL::type('User'),
                'importance' => 220,
            ],
            'secret' => [
                'description' => "A string of characters that can be considered as a *shared secret* between the server
                                  and the client. This string is needed to authorize the *application itself* to the
                                  server.
                                  \n\n**IMPORTANT!** It is assumed that only the application and the server know this
                                  string. If you use this value (or even display it), make sure that it is not to easy
                                  for third parties to steal the shared secret..",
                'type' => GraphQL::type('String'),
                'importance' => 210,
            ],
            'type' => [
                'description' => "The type of `OAuthClient`, which determines how the client should access the API's
                                  on the server.",
                'type' => GraphQL::type('OAuthClientType'),
                'importance' => 200,
            ],
            'revoked' => [
                'description' => 'Whether or not the `OAuthClient` is revoked. The server denies all requests from
                                  clients that are revoked.',
                'type' => GraphQL::type('Boolean'),
                'importance' => 100,
            ]
        ];
    }

    /**
     * Returns the definitions of the connections
     */
    protected function connections()
    {
        return [
            'tokens' => [
                'to' => 'OAuthToken',
                'description' => 'A list of all the access tokens of this client.'
            ]
        ];
    }

    public function getInterfaces()
    {
        return [GraphQL::type('Model')];
    }

    public function resolveType($objectValue, $context, ResolveInfo $info)
    {
        if($objectValue instanceof Client) {
            $typeName = $objectValue->type->getGraphQLTypeName();
            return GraphQL::type($typeName);
        }

        throw new InvariantViolation("Can't find the OAuthClient-type.");
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENT: ModelTypeContract ----------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the name of the Model that this ModelType represents.
     *
     * @return string
     */
    public function getModelClass()
    {
        return Client::class;
    }

    /**
     * Gives if you can order a list of this ModelTypes.
     *
     * @return boolean
     */
    public function orderable()
    {
        return true;
    }

    protected $orderByInputType;

    /**
     * Returns the OrderByInputType that belongs to this Model-type.
     *
     * @return OrderByInputType
     */
    public function getOrderByInputType()
    {
        if($this->orderByInputType === null) {
            $this->orderByInputType = new OrderByInputType([
                'orderables' => [
                    'id' => [
                        'description' => 'Orders by the `ID` of the `OAuthClient`.',
                        'query' => ['id'],
                        'cursorPattern' => ['id' => 'n'],
                    ],
                    'name' => [
                        'description' => 'Orders the `OAuthClient`s by the name',
                        'query' => ['name','id'],
                        'cursorPattern' => ['name' => 'a*','id' => 'n'],
                    ],
                    'createdAt' => [
                        'description' => 'Orders the moment that the `OAuthClient` was created.',
                        'query' => ['created_at','id'],
                        'cursorPattern' => ['created_at' => 'datetime','id' => 'n'],
                    ],
                    'updatedAt' => [
                        'description' => 'Orders the `OAuthClient`s by the last time they were updated.',
                        'query' => ['updated_at', 'id'],
                        'cursorPattern' => ['updated_at' => 'datetime','id' => 'n'],
                    ]
                ],
                'modelType' => $this
            ]);
        }
        return $this->orderByInputType;
    }

    /**
     * Gives if you can use filters on a list of this ModelTypes.
     *
     * @return boolean
     */
    public function filterable()
    {
        return true;
    }

    protected $filterInputType;

    /**
     * Gives the filter input type that belongs to this Model-type.
     *
     * @return FilterInputType
     */
    public function getFilterInputType()
    {
        if ($this->filterInputType === null) {
            $this->filterInputType = new FilterInputType([
                'filters' => [
                    'type' => [
                        'type' => GraphQL::type('OAuthClientType'),
                        'description' => 'Filters the OAuthClient with the provided client type.'
                    ],

                    'anyType' => [
                        'type' => GraphQL::type('[OAuthClientType!]'),
                        'description' => 'Filters the OAuthClients that are of one of the given types.'
                    ],

                    'revoked' => [
                        'type' => GraphQL::type('Boolean'),
                        'description' => 'Filters the OAuthClients that are revoked (or not).'
                    ],

                    'name' => [
                        'type' => GraphQL::type('String'),
                        'description' => 'Filters the OAuthClients that contain the provided string in their name.'
                    ]
                ],
                'modelType' => $this
            ]);
        }
        return $this->filterInputType;
    }

    /**
     * Gives if you can text-based-search on this ModelType.
     *
     * @return boolean
     */
    public function searchable()
    {
        return false;
    }
}