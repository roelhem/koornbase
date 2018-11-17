<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:16
 */

namespace App\Actions\Models\Update;


use App\PersonPhoneNumber;
use Roelhem\GraphQL\Facades\GraphQL;

class UpdatePersonPhoneNumberAction extends AbstractUpdateAction
{
    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the PersonPhoneNumber that you want to update',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:person_phone_numbers'],
            ],
            'label' => [
                'description' => 'A new label for the PersonPhoneNumber is Updated.',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','string','max:255'],
            ],
            'remarks' => [
                'description' => 'Some remarks associated with updated phone number',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
            'phoneNumber' => [
                'description' => 'The updated phone number.',
                'alias' => 'phone_number',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','phone'],
            ],
            'countryCode' => [
                'description' => 'The country code of the country where the (newly updated) phone number is registered.',
                'alias' => 'country_code',
                'type' => GraphQL::type('CountryCode'),
            ]
        ];
    }

    public function afterValidation($validator)
    {
        parent::afterValidation($validator);

        $data = $validator->getData();
        $id = array_get($data,'id');
        /** @var PersonPhoneNumber $model */
        $model = PersonPhoneNumber::findOrFail($id);

        $label = array_get($data,'id');
        if($label !== null && $model->label !== $label) {
            if($model->person->phoneNumbers()->where('label','=', $label)->exists()) {
                $validator->errors()->add('label','This label is not unique for this person.');
            }
        }
    }
}