<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:45
 */

namespace App\Actions\Models\Create;


use App\Person;
use Roelhem\GraphQL\Facades\GraphQL;

class CreatePersonAddressAction extends AbstractCreateAction
{

    protected $description = 'Creates a new `Address` entry for a `Person`.';


    /** @inheritdoc */
    public function afterValidation($validator)
    {
        $data = $validator->getData();

        // Get the person instance to who the membership will be created.
        $person_id = array_get($data, 'person_id');
        $person = Person::find($person_id);
        if(!($person instanceof Person)) {
            $validator->errors()->add('person_id','Can\'t find a person with person_id: '.$person_id.'.');
            return;
        }

        // Checking if the label is unique for this person.
        $label = array_get($data, 'label');
        if($person->addresses()->where('label','=',$label)->exists()) {
            $validator->errors()->add('label','There already exists an address for this person with this label.');
        }
    }

    /**
     * Method that returns the definition of the available arguments of this action.
     *
     * @return array
     */
    public function args()
    {
        return [
            'personId' => [
                'description' => 'The `ID` of the Person where this address belongs to.',
                'alias' => 'person_id',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:persons'],
            ],
            'label' => [
                'description' => 'A short label for the new address that is unique of the Person.',
                'type' => GraphQL::type('String!'),
                'rules' => ['required','string','max:255'],
            ],
            'remarks' => [
                'description' => 'Some remarks associated with the newly added address',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
            'countryCode' => [
                'description' => 'The Country-code of the country where the address is located',
                'alias' => 'country_code',
                'type' => GraphQL::type('CountryCode'),
                'rules' => ['country_code']
            ],
            'administrativeArea' => [
                'type' => GraphQL::type('String'),
                'alias' => 'administrative_area',
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'locality' => [
                'type' => GraphQL::type('String'),
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'dependentLocality' => [
                'type' => GraphQL::type('String'),
                'alias' => 'dependent_locality',
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'postalCode' => [
                'type' => GraphQL::type('String'),
                'alias' => 'postal_code',
                'rules' => ['address_field','postal_code','nullable','string','max:255'],
            ],
            'sortingCode' => [
                'type' => GraphQL::type('String'),
                'alias' => 'sorting_code',
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'addressLine1' => [
                'type' => GraphQL::type('String'),
                'alias' => 'address_line_1',
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'addressLine2' => [
                'type' => GraphQL::type('String'),
                'alias' => 'address_line_2',
                'rules' => ['address_field','nullable','string','max:255'],
            ],
            'organisation' => [
                'type' => GraphQL::type('String'),
                'rules' => ['address_field','nullable','string','max:255'],
            ],
        ];
    }
}