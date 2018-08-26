<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 14:48
 */

namespace App\Http\GraphQL\Mutations;


use GraphQL\Type\Definition\Type;
use Laravel\Passport\ClientRepository;
use Rebing\GraphQL\Support\Mutation;

class RevokeOAuthClientMutation extends Mutation
{

    /**
     * @var ClientRepository
     */
    protected $clients;


    protected $attributes = [
        'name' => 'revokeOAuthClient',
        'description' => 'Revokes an OAuthClient so that all the requests of this client will be denied.'
    ];

    /**
     * NewOAuthClientMutation constructor.
     * @param ClientRepository $clients
     * @param array $attributes
     */
    public function __construct(ClientRepository $clients, $attributes = [])
    {
        $this->clients = $clients;
        parent::__construct($attributes);
    }


    /** @inheritdoc */
    public function type()
    {
        return \GraphQL::type('OAuthClient');
    }

    /** @inheritdoc */
    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the OAuthClient that will be revoked.',
                'rules' => ['required','exists:oauth_clients,id'],
            ]
        ];
    }

    /** @inheritdoc */
    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        $client = $this->clients->find($id);
        $this->clients->delete($client);
        return $client;
    }
}