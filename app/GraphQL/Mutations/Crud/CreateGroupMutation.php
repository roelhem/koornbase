<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 03:15
 */

namespace App\GraphQL\Mutations\Crud;


use App\Group;
use App\GroupCategory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class CreateGroupMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createGroup',
        'description' => 'Creates a new Group.',
    ];

    public function type()
    {
        return \GraphQL::type('Group');
    }

    public function args()
    {
        return [
            'category_id' => [
                'description' => 'The `ID` of the GroupCategory to which this new Group should belong.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:group_categories'],
            ],
            'name' => [
                'description' => 'The name for the new Group',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255','unique:groups'],
            ],
            'name_short' => [
                'description' => 'A short version of the name.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'member_name' => [
                'description' => 'The name that you should call a person that is a member of this new Group.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:255'],
            ],
            'description' => [
                'description' => 'A description of the group.',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
        ];
    }

    public function resolve($root, $args) {
        $category_id = array_get($args, 'category_id');
        /** @var GroupCategory $category */
        $category = GroupCategory::findOrFail($category_id);

        return $category->groups()->create(array_except($args, ['category_id']));
    }

}