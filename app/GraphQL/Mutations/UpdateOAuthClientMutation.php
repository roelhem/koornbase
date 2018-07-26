<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 14:14
 */

namespace App\GraphQL\Mutations;


use App\OAuth\Client;
use GraphQL\Type\Definition\Type;
use Laravel\Passport\ClientRepository;
use Rebing\GraphQL\Support\Mutation;



class UpdateOAuthClientMutation extends Mutation
{

    /**
     * @var ClientRepository
     */
    protected $clients;


    protected $attributes = [
        'name' => 'updateOAuthClient',
        'description' => 'Edits the name and/or redirect-url of an already existing OAuthClient.'
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
                'description' => 'The `ID` of the OAuthClient that should be updated.',
                'rules' => ['required','exists:oauth_clients,id'],
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The new name of the client. If this value is unspecified or `null`, the old name will be used.',
                'rules' => ['nullable','string','max:255'],
            ],
            'redirect' => [
                'type' => Type::string(),
                'description' => 'The new URL to which an User should be redirected after it authorized the client. If this value is unspecified or `null`, the redirect-URL will be used.',
                'rules' => ['nullable','url']
            ]
        ];
    }

    /** @inheritdoc */
    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');

        /** @var Client $client */
        $client = $this->clients->find($id);

        $name     = array_get($args, 'name') ?? $client->name;
        $redirect = array_get($args, 'redirect') ?? $client->redirect;

        return $this->clients->update($client, $name, $redirect);
    }

}