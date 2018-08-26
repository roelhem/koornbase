<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 03:44
 */

namespace App\Http\GraphQL\Mutations\Crud\Create;


use App\Group;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class CreateGroupEmailAddressMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createGroupEmailAddress',
        'description' => 'Creates a new email-address entry that is linked to a Group.'
    ];

    public function type()
    {
        return \GraphQL::type('GroupEmailAddress');
    }

    public function args() {
        return [
            'group_id' => [
                'description' => 'The `ID` of the Group that should be associated with this new email-address.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:groups,id'],
            ],
            'email_address' => [
                'description' => 'The new email-address itself.',
                'type' => Type::nonNull(\GraphQL::type('Email')),
                'rules' => ['required','email','max:255','unique:group_email_addresses'],
            ],
            'remarks' => [
                'description' => 'Some extra remarks associated with the newly created email_address',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
        ];
    }

    public function resolve($root, $args) {
        $group_id = array_get($args, 'group_id');
        /** @var Group $group */
        $group = Group::findOrFail($group_id);

        return $group->emailAddresses()->create(array_except($args,'group_id'));
    }
}