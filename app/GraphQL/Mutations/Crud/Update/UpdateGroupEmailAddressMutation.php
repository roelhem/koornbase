<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 03:58
 */

namespace App\GraphQL\Mutations\Crud\Update;


use App\GroupEmailAddress;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class UpdateGroupEmailAddressMutation extends Mutation
{

    protected $attributes = [
        'name' => 'updateGroupEmailAddress',
        'description' => 'Updates the values of a e-mail address that is associated with a Group.'
    ];

    public function type()
    {
        return \GraphQL::type('GroupEmailAddress');
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the GroupEmailAddress that is going to be updated',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:group_email_addresses'],
            ],
            'email_address' => [
                'description' => 'The new email address for the GroupEmailAddress that is updated.',
                'type' => Type::string(),
                'rules' => ['sometimes','required','email','max:255'],
            ],
            'remarks' => [
                'description' => 'The updated remarks about the email address.',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        /** @var GroupEmailAddress $emailAddress */
        $emailAddress = GroupEmailAddress::findOrFail($id);

        $email_address = array_get($args,'email_address');
        if($email_address !== null && $emailAddress->email_address !== $email_address) {
            if(GroupEmailAddress::where('email_address','=',$emailAddress)->exists()) {
                new ValidationError('There already exists a GroupEmailAddress with the provided email_address');
            }
        }

        $emailAddress->fill(array_except($args, ['id']));
        $emailAddress->saveOrFail();

        return $emailAddress;
    }

}