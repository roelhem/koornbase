<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 04:36
 */

namespace App\GraphQL\Mutations\Crud\Update;


use App\PersonEmailAddress;
use App\PersonPhoneNumber;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdatePersonPhoneNumberMutation extends Mutation
{

    protected $attributes = [
        'name' => 'updatePersonPhoneNumber',
        'description' => 'Updates the values of a phone number that is associated with a Person.'
    ];

    public function type()
    {
        return \GraphQL::type('PersonPhoneNumber');
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the PersonPhoneNumber that you want to update',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:person_phone_numbers'],
            ],
            'label' => [
                'description' => 'A new label for the PersonPhoneNumber is Updated.',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['sometimes','required','string','max:255'],
            ],
            'remarks' => [
                'description' => 'Some remarks associated with updated phone number',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
            'phone_number' => [
                'description' => 'The updated phone number.',
                'type' => Type::string(),
                'rules' => ['sometimes','required','phone'],
            ],
            'country_code' => [
                'description' => 'The country code of the country where the (newly updated) phone number is registered.',
                'type' => \GraphQL::type('CountryCode'),
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        /** @var PersonPhoneNumber $emailAddress */
        $phoneNumber = PersonPhoneNumber::findOrFail($id);

        $label = array_get($args, 'label');
        if($label !== null && $phoneNumber->label !== $label) {
            if($phoneNumber->person->phoneNumbers()->where('label', '=',$label)->exists()) {
                throw new ValidationError("There already exists an PersonPhoneNumber of this Person that has the same label.");
            }
        }

        $phoneNumber->fill(array_except($args, ['id']));
        $phoneNumber->saveOrFail();
        return $phoneNumber;
    }

}