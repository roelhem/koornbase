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
                'name' => 'id',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required']
            ],
            'name_first' => [
                'name' => 'name_first',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','string','max:255']
            ],
            'name_middle' => [
                'name' => 'name_middle',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:255'],
            ],
            'name_prefix' => [
                'name' => 'name_prefix',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'name_last' => [
                'name' => 'name_last',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','string','max:255']
            ],
            'name_initials' => [
                'name' => 'name_initials',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'name_nickname' => [
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:255'],
            ],
            'birth_date' => [
                'name' => 'birth_date',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','before:now']
            ],
            'remarks' => [
                'name' => 'remarks',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ]
        ];
    }

}