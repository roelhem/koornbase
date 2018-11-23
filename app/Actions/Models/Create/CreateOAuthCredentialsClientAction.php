<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 23/11/2018
 * Time: 15:18
 */

namespace App\Actions\Models\Create;

use App\Enums\OAuthClientType;
use App\OAuth\Client;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class CreateOAuthCredentialsClientAction extends AbstractCreateAction
{
    protected $modelClass = Client::class;

    /**
     * Handles the action with all the validated arguments.
     *
     * @param array $validArgs
     * @param null|ActionContext $context
     * @return mixed
     */
    protected function handle($validArgs = [], ?ActionContext $context = null)
    {
        return OAuthClientType::CREDENTIALS()->create($validArgs);
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
                'description' => 'The name of the new credentials client.',
                'rules' => ['required','string','max:255'],
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