<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:05
 */

namespace App\Actions\Models\Update;


use Roelhem\GraphQL\Facades\GraphQL;

class UpdateGroupEmailAddressAction extends AbstractUpdateAction
{
    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the GroupEmailAddress that is going to be updated',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:group_email_addresses'],
            ],
            'emailAddress' => [
                'description' => 'The new email address for the GroupEmailAddress that is updated.',
                'alias' => 'email_address',
                'type' => GraphQL::type('Email'),
                'rules' => ['sometimes','required','email','max:255','unique_or_same:group_email_addresses'],
            ],
            'remarks' => [
                'description' => 'The updated remarks about the email address.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ]
        ];
    }
}