<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 22:05
 */

namespace App\Actions\Models\Update;



use Roelhem\GraphQL\Facades\GraphQL;

class UpdatePersonAction extends AbstractUpdateAction
{

    public function args()
    {
        return [
            'id' => [
                'type' => GraphQL::type('ID!'),
                'rules' => ['required']
            ],
            'firstName' => [
                'alias' => 'name_first',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','string','max:255']
            ],
            'middleName' => [
                'alias' => 'name_middle',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:255'],
            ],
            'prefixName' => [
                'alias' => 'name_prefix',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'lastName' => [
                'alias' => 'name_last',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','string','max:255']
            ],
            'initials' => [
                'alias' => 'name_initials',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'nickname' => [
                'alias' => 'name_nickname',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:255'],
            ],
            'birthDate' => [
                'alias' => 'birth_date',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','before:now']
            ],
            'remarks' => [
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ]
        ];
    }

}