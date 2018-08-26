<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 04:36
 */

namespace App\GraphQL\Mutations\Crud\Update;


use App\PersonEmailAddress;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class UpdatePersonEmailAddressMutation extends Mutation
{

    protected $attributes = [
        'name' => 'updatePersonEmailAddress',
        'description' => 'Updates the values of an email address that is associated with a Person.'
    ];

    public function type()
    {
        return \GraphQL::type('PersonEmailAddress');
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the PersonEmailAddress that you want to update',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:person_email_addresses'],
            ],
            'label' => [
                'description' => 'A new label for the PersonEmailAddress is Updated.',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['sometimes','required','string','max:255'],
            ],
            'remarks' => [
                'description' => 'Some remarks associated with the newly added email-address',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
            'email_address' => [
                'description' => 'The updated email address.',
                'type' => Type::string(),
                'rules' => ['sometimes','required','email','max:255'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        /** @var PersonEmailAddress $emailAddress */
        $emailAddress = PersonEmailAddress::findOrFail($id);

        $label = array_get($args, 'label');
        if($label !== null && $emailAddress->label !== $label) {
            if($emailAddress->person->emailAddresses()->where('label', '=',$label)->exists()) {
                throw new ValidationError("There already exists an PersonEmailAddress of this Person that has the same label.");
            }
        }

        $emailAddress->fill(array_except($args, ['id']));
        $emailAddress->saveOrFail();
        return $emailAddress;
    }

}