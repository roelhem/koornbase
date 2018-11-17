<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:43
 */

namespace App\Actions\Models\Create;

use Roelhem\GraphQL\Facades\GraphQL;

class CreateGroupCategoryAction extends AbstractCreateAction
{

    protected $description = 'Creates a new GroupCategory.';

    /**
     * Method that returns the definition of the available arguments of this action.
     *
     * @return array
     */
    public function args()
    {
        return [
            'name' => [
                'description' => 'The name for the new GroupCategory',
                'type' => GraphQL::type('String!'),
                'rules' => ['required','string','max:255','unique:group_categories'],
            ],
            'shortName' => [
                'description' => 'A short version of the name.',
                'alias' => 'shortName',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'description' => [
                'description' => 'A description of the category.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
            'style' => [
                'description' => 'The name of the style in which this category and all the groups of this category should be displayed',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ]
        ];
    }
}