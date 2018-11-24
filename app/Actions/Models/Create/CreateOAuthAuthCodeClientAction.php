<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:45
 */

namespace App\Actions\Models\Create;


use App\Enums\OAuthClientType;
use App\OAuth\Client;
use Laravel\Passport\ClientRepository;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class CreateOAuthAuthCodeClientAction extends AbstractCreateAction
{

    protected $modelClass = Client::class;

    /**
     * @var ClientRepository
     */
    protected $repository;

    /**
     * CreateOAuthAuthCodeClientAction constructor.
     * @param ClientRepository $repository
     */
    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handles the action with all the validated arguments.
     *
     * @param array $validArgs
     * @param null|ActionContext $context
     * @return mixed
     */
    protected function handle($validArgs = [], ?ActionContext $context = null)
    {
        $userId = array_get($validArgs,'user_id',$context->user()->getId());
        $name = array_get($validArgs, 'name');
        $redirect = array_get($validArgs,'redirect');

        return $this->repository->create($userId,$name,$redirect);
    }

    /**
     * Method that returns the definition of the available arguments of this action.
     *
     * @return array
     */
    public function args()
    {
        return [
            'name' => [
                'type' => GraphQL::type('String!'),
                'description' => 'The name of the new client. This will be displayed when the client requests an User authorize.',
                'rules' => ['required','string','max:255'],
            ],
            'redirect' => [
                'type' => GraphQL::type('String!'),
                'description' => 'The URL to which an User is redirected after it authorized the client.',
                'rules' => ['required','url']
            ],
            'userId' => [
                'type' => GraphQL::type('ID'),
                'alias' => 'user_id',
                'description' => 'The `ID` of the User that should manage this client. If this value is ommitted or set to `null`, the `ID` of the current User will be used.',
                'rules' => ['nullable','exists:users,id']
            ],
        ];
    }
}