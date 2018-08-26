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
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class CreatePersonPhoneNumberMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createPersonPhoneNumber',
        'description' => 'Adds a new phone number for a Person.'
    ];

    public function type()
    {
        return \GraphQL::type('PersonPhoneNumber');
    }

    public function args()
    {
        return [
            'person_id' => [
                'description' => 'The `ID` of the Person where this new phone number belongs to.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:persons'],
            ],
            'label' => [
                'description' => 'A short label for the new phone number that is unique of the Person.',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255'],
            ],
            'remarks' => [
                'description' => 'Some remarks associated with the newly added phone number.',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
            'phone_number' => [
                'description' => 'The phone number to add.',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','phone'],
            ],
            'country_code' => [
                'description' => 'The country code of the country where the phone number is registered.',
                'type' => \GraphQL::type('CountryCode'),
                'rules' => ['country_code'],
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $person_id = array_get($args, 'person_id');
        /** @var Person $person */
        $person = Person::findOrFail($person_id);

        $label = array_get($args, 'label');
        if($person->phoneNumbers()->where('label','=',$label)->exists()) {
            throw new ValidationError('There already exists a PhoneNumber of this Person with the same label.');
        }

        return $person->phoneNumbers()->create(array_except($args, ['person_id']));
    }

}