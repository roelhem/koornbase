<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 13:41
 */

namespace App\Actions\Models\Create;


use App\GroupCategory;
use Roelhem\Actions\Contracts\ActionContextContract;
use Roelhem\GraphQL\Facades\GraphQL;

class CreateGroupAction extends AbstractCreateAction
{

    protected $description = "Creates a new `Group` in the database.";

    /** @inheritdoc */
    protected function handle($validArgs = [], ?ActionContextContract $context = null)
    {
        $category_id = array_get($validArgs, 'category_id');
        /** @var GroupCategory $category */
        $category = GroupCategory::findOrFail($category_id);

        return $category->groups()->create(array_except($validArgs, ['category_id']));
    }


    public function args()
    {
        return [
            'category_id' => [
                'description' => 'The `ID` of the GroupCategory to which this new Group should belong.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:group_categories,id'],
            ],
            'name' => [
                'description' => 'The name for the new Group',
                'type' => GraphQL::type('String!'),
                'rules' => ['required','string','max:255','unique:groups'],
            ],
            'name_short' => [
                'description' => 'A short version of the name.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'member_name' => [
                'description' => 'The name that you should call a person that is a member of this new Group.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:255'],
            ],
            'description' => [
                'description' => 'A description of the group.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
        ];
    }
}