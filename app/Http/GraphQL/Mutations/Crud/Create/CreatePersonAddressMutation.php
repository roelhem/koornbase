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

class CreatePersonAddressMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createPersonAddress',
        'description' => 'Adds a new (postal) address for a Person.'
    ];

    public function type()
    {
        return \GraphQL::type('PersonAddress');
    }

    public function args()
    {
        return [
            'person_id' => [
                'description' => 'The `ID` of the Person where this address belongs to.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:persons'],
            ],
            'label' => [
                'description' => 'A short label for the new address that is unique of the Person.',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255'],
            ],
            'remarks' => [
                'description' => 'Some remarks associated with the newly added address',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
            'country_code' => [
                'description' => 'The Country-code of the country where the address is located',
                'type' => \GraphQL::type('CountryCode'),
                'rules' => ['country_code']
            ],
            'adminstative_area' => [
                'type' => Type::string(),
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'locality' => [
                'type' => Type::string(),
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'dependent_locality' => [
                'type' => Type::string(),
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'postal_code' => [
                'type' => Type::string(),
                'rules' => ['address_field','postal_code','nullable','string','max:255'],
            ],
            'sorting_code' => [
                'type' => Type::string(),
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'address_line_1' => [
                'type' => Type::string(),
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'address_line_2' => [
                'type' => Type::string(),
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'organisation' => [
                'type' => Type::string(),
                'rules' => ['address_field','nullable','string','max:255'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $person_id = array_get($args, 'person_id');
        /** @var Person $person */
        $person = Person::findOrFail($person_id);

        $label = array_get($args, 'label');
        if($person->addresses()->where('label','=',$label)->exists()) {
            throw new ValidationError('There already exists a PersonAddress of this Person with the same label.');
        }

        return $person->addresses()->create(array_except($args, ['person_id']));
    }

}