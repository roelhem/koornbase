<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:13
 */

namespace App\Actions\Models\Update;


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
}