<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:03
 */

namespace App\Actions\Models\Update;


use Roelhem\GraphQL\Facades\GraphQL;

class UpdateGroupCategoryAction extends AbstractUpdateAction
{
    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the GroupCategory that you want to update.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:group_categories']
            ],
            'name' => [
                'description' => 'A new name for the GroupCategory.',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','string','max:255'],
            ],
            'shortName' => [
                'description' => 'A new short version of the name of the GroupCategory.',
                'alias' => 'name_short',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'description' => [
                'description' => 'A new description for the GroupCategory',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
            'style' => [
                'description' => 'The name of a new style in which the category and all the groups that belong to this category should be displayed',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ]
        ];
    }
}