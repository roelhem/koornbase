<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:43
 */

namespace App\Actions\Models\Create;


use App\Group;
use Roelhem\Actions\Contracts\ActionContextContract;
use Roelhem\GraphQL\Facades\GraphQL;

class CreateGroupEmailAddressAction extends AbstractCreateAction
{

    /**
     * Handles the action with all the validated arguments.
     *
     * @param array $validArgs
     * @param null|ActionContextContract $context
     * @return mixed
     */
    protected function handle($validArgs = [], ?ActionContextContract $context = null)
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
            'group_id' => [
                'description' => 'The `ID` of the Group that should be associated with this new email-address.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:groups,id'],
            ],
            'email_address' => [
                'description' => 'The new email-address itself.',
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