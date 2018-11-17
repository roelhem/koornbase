<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:09
 */

namespace App\Actions\Models\Update;


use Roelhem\GraphQL\Facades\GraphQL;

class UpdateMembershipAction extends AbstractUpdateAction
{

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `Membership` that should be updated.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:memberships'],
            ],
            'application' => [
                'description' => 'The newly updated date on which the owner of this membership applied for the Koornbeurs Membership (and thus started its `NOVICE` phase of the membership.)',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'start' => [
                'description' => 'The newly updated date on which the membership was officially started.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application'],
            ],
            'end' => [
                'description' => 'The newly updated date on which the membership was ended.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:application,start']
            ],
            'remarks' => [
                'description' => 'The new remarks regarding the membership.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string']
            ]
        ];
    }
}