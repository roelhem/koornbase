<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 23:34
 */

namespace App\Actions\Models\Update;


use Roelhem\GraphQL\Facades\GraphQL;

class UpdateUserAction extends AbstractUpdateAction
{

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `User` that you want to update.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required']
            ],
            'personId' => [
                'description' => 'The `ID` of the `Person` to whom this `User` should belong to',
                'alias' => 'person_id',
                'type' => GraphQL::type('ID'),
                'rules' => ['nullable','exists:persons,id']
            ]
        ];
    }
}