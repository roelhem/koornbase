<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:07
 */

namespace App\Actions\Models\Update;


use Roelhem\GraphQL\Facades\GraphQL;

class UpdateGroupAction extends AbstractUpdateAction
{
    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the Group that you want to update.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:groups'],
            ],
            'name' => [
                'description' => 'The new name for the updated Group.',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','string','max:255','unique_or_same:groups'],
            ],
            'shortName' => [
                'description' => 'The new shorter version of the name for the Group.',
                'alias' => 'name_short',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'memberName' => [
                'description' => 'The new name for Persons that belong to this Group.',
                'alias' => 'member_name',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:255'],
            ],
            'description' => [
                'description' => 'The new description for the Group.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
            'categoryId' => [
                'description' => 'The `ID` of the new `GroupCategory` for this Group.',
                'alias' => 'category_id',
                'type' => GraphQL::type('ID'),
                'rules' => ['sometimes','required','exists:group_categories,id']
            ]
        ];
    }
}