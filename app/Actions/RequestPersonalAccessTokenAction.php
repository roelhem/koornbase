<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 16:57
 */

namespace App\Actions;



use App\Enums\OAuthClientType;
use App\Enums\OAuthScope;
use App\OAuth\Client;
use App\Services\PersonalAccessTokenFactory;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class RequestPersonalAccessTokenAction extends AbstractAction
{

    protected $name = 'requestPersonalAccessToken';

    protected $description = 'Requests to issue an access token from an `OAuthPersonalClient`. This token is then 
                              available for the current user to authorize requests during the development of client
                              applications.';

    protected $type = 'OAuthTokenIssue';

    /** @var PersonalAccessTokenFactory $factory     The factory class that will create the access-token. */
    protected $factory;

    /**
     * RequestPersonalAccessTokenAction constructor.
     * @param PersonalAccessTokenFactory $factory
     */
    public function __construct(PersonalAccessTokenFactory $factory)
    {
        $this->factory = $factory;
    }

    /** @inheritdoc */
    public function args()
    {
        return [
            'name' => [
                'description' => 'The name that the requested OAuth access-token should have.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:255'],
            ],
            'clientId' => [
                'description' => 'The `ID` of the `OAuthPersonalClient` to whom the access token should belong.',
                'type' => GraphQL::type('ID'),
            ],
            'userId' => [
                'description' => 'The `ID` of the `User` to whom this access token should be issued. The token will
                                  then authorize requests for this user.
                                  
                                  If this argument is omitted, the name of the current user will be used instead.',
                'type' => GraphQL::type('ID'),
                'rules' => ['sometimes','required','exists:users,id'],
            ],
            'scopes' => [
                'description' => 'An list of `OAuthScope`s that are granted when this access-token is used.',
                'type' => GraphQL::type('[OAuthScope]')
            ]
        ];
    }

    /** @inheritdoc */
    public function handle($validArgs = [], ?ActionContext $context = null)
    {
        // Getting the User-id
        $userId = array_get($validArgs,'userId') ?? $context->user()->getId();

        // Getting the name for the access token.
        $name = array_get($validArgs,'name');

        // Getting the scopes
        $scopeArg = array_get($validArgs,'scopes');
        if(empty($scopeArg)) {
            $scopes = [];
        } else {
            $scopes = array_map(function($scope) {
                if($scope instanceof OAuthScope) {
                    return $scope->getName();
                } else {
                    return $scope;
                }
            }, $scopeArg);
        }

        // Getting the ID of the client.
        $clientId = array_get($validArgs,'clientId');

        // Making the result;
        if(empty($clientId)) {
            // Using the default personal access client when no clientId was provided.
            return $this->factory->make($userId, $name, $scopes);
        } else {
            // Retrieving and validating the accessClient.
            /** @var Client $client */
            $client = Client::findOrFail($clientId);
            if($client->revoked) {
                throw new \Exception("Can't get a access token from a revoked client.");
            }
            if(!$client->type->is(OAuthClientType::PERSONAL)) {
                throw new \Exception("Only Personal Access Clients can provide Personal Access Tokens.");
            }

            // Using the personal access client specified in the clientId argument.
            return $this->factory->makeWithClient($client, $userId, $name, $scopes);
        }
    }
}