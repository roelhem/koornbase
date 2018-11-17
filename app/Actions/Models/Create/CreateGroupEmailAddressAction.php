<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:43
 */

namespace App\Actions\Models\Create;


use App\Group;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

class CreateGroupEmailAddressAction extends AbstractCreateAction
{

    /**
     * Handles the action with all the validated arguments.
     *
     * @param array $validArgs
     * @param null|ActionContext $context
     * @return mixed
     */
    protected function handle($validArgs = [], ?ActionContext $context = null)
    {
        $group_id = array_get($validArgs, 'group_id');
        /** @var Group $group */
        $group = Group::findOrFail($group_id);

        return $group->emailAddresses()->create(array_except($validArgs,'group_id'));
    }

    /**
     * Method that returns the definition of the available arguments of this action.
     *
     * @return array
     */
    public function args()
    {
        return [
            'groupId' => [
                'description' => 'The `ID` of the Group that should be associated with this new email-address.',
                'alias' => 'group_id',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:groups,id'],
            ],
            'emailAddress' => [
                'description' => 'The new email-address itself.',
                'alias' => 'email_address',
                'type' => GraphQL::type('Email!'),
                'rules' => ['required','email','max:255','unique:group_email_addresses'],
            ],
            'remarks' => [
                'description' => 'Some extra remarks associated with the newly created email_address',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
        ];
    }
}