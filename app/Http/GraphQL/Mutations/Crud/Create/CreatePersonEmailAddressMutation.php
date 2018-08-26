<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 04:36
 */

namespace App\Http\GraphQL\Mutations\Crud\Create;


use App\Person;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class CreatePersonEmailAddressMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createPersonEmailAddress',
        'description' => 'Adds a new e-mail address for a Person.'
    ];

    public function type()
    {
        return \GraphQL::type('PersonEmailAddress');
    }

    public function args()
    {
        return [
            'person_id' => [
                'description' => 'The `ID` of the Person where this new email address belongs to.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:persons'],
            ],
            'label' => [
                'description' => 'A short label for the new email address that is unique of the Person.',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255'],
            ],
            'remarks' => [
                'description' => 'Some remarks associated with the newly added email-address',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
            'email_address' => [
                'description' => 'The E-mail address to add.',
                'type' => Type::nonNull(\GraphQL::type('Email')),
                'rules' => ['required','email','max:255'],
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $person_id = array_get($args, 'person_id');
        /** @var Person $person */
        $person = Person::findOrFail($person_id);
    }


}