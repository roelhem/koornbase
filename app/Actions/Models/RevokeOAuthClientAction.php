<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 16:07
 */

namespace App\Actions\Models;


use App\OAuth\Client;
use Laravel\Passport\ClientRepository;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class RevokeOAuthClientAction extends AbstractModelAction
{
    protected $description = 'Revokes an OAuthClient so that all the requests of this client will be denied.';

    protected $type = 'OAuthClient';

    protected $modelClass = Client::class;

    /** @var ClientRepository */
    protected $clients;

    /**
     * RevokeOAuthClientAction constructor.
     * @param ClientRepository $clients
     */
    public function __construct(ClientRepository $clients)
    {
        $this->clients = $clients;
    }

    /** @inheritdoc */
    public function args()
    {
        return [
            'id' => [
                'type' => GraphQL::type('ID!'),
                'description' => 'The `ID` of the `OAuthClient` that will be revoked.',
                'rules' => ['required','exists:oauth_clients,id'],
            ]
        ];
    }

    /** @inheritdoc */
    public function handle($validArgs = [], ?ActionContext $context = null)
    {
        $id = array_get($validArgs,'id');
        $client = $this->clients->find($id);
        $this->clients->delete($client);
        return $client;
    }
}