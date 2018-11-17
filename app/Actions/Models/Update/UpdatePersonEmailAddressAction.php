<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:13
 */

namespace App\Actions\Models\Update;


use App\PersonEmailAddress;
use Roelhem\GraphQL\Facades\GraphQL;

class UpdatePersonEmailAddressAction extends AbstractUpdateAction
{
    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the PersonEmailAddress that you want to update',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:person_email_addresses'],
            ],
            'label' => [
                'description' => 'A new label for the PersonEmailAddress is Updated.',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','string','max:255'],
            ],
            'remarks' => [
                'description' => 'Some remarks associated with the newly added email-address',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
            'emailAddress' => [
                'description' => 'The updated email address.',
                'alias' => 'email_address',
                'type' => GraphQL::type('Email'),
                'rules' => ['sometimes','required','email','max:255'],
            ],
        ];
    }

    public function afterValidation($validator)
    {
        parent::afterValidation($validator);

        $data = $validator->getData();
        $id = array_get($data,'id');
        /** @var PersonEmailAddress $model */
        $model = PersonEmailAddress::findOrFail($id);

        $label = array_get($data,'id');
        if($label !== null && $model->label !== $label) {
            if($model->person->emailAddresses()->where('label','=', $label)->exists()) {
                $validator->errors()->add('label','This label is not unique for this person.');
            }
        }
    }
}