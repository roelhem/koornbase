<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:46
 */

namespace App\Actions\Models\Create;

use App\Person;
use Roelhem\GraphQL\Facades\GraphQL;

class CreatePersonPhoneNumberAction extends AbstractCreateAction
{

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
        if($person->phoneNumbers()->where('label','=',$label)->exists()) {
            $validator->errors()->add('label','There already exists an phone number for this person with this label.');
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
                'description' => 'The `ID` of the Person where this new phone number belongs to.',
                'alias' => 'person_id',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:persons'],
            ],
            'label' => [
                'description' => 'A short label for the new phone number that is unique of the Person.',
                'type' => GraphQL::type('String!'),
                'rules' => ['required','string','max:255'],
            ],
            'remarks' => [
                'description' => 'Some remarks associated with the newly added phone number.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
            'phoneNumber' => [
                'description' => 'The phone number to add.',
                'alias' => 'phone_number',
                'type' =>GraphQL::type('String!'),
                'rules' => ['required','phone'],
            ],
            'countryCode' => [
                'description' => 'The country code of the country where the phone number is registered.',
                'alias' => 'country_code',
                'type' => GraphQL::type('CountryCode'),
                'rules' => ['country_code'],
            ]
        ];
    }
}