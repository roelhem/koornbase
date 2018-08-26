<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 03:02
 */

namespace App\Http\GraphQL\Mutations\Crud\Update;



use App\GroupCategory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class UpdateGroupCategoryMutation extends Mutation
{

    protected $attributes = [
        'name' => 'updateGroupCategory',
        'description' => 'Updates the values of an existing GroupCategory',
    ];

    public function type()
    {
        return \GraphQL::type('GroupCategory');
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the GroupCategory that you want to update.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:group_categories']
            ],
            'name' => [
                'description' => 'A new name for the GroupCategory.',
                'type' => Type::string(),
                'rules' => ['sometimes','required','string','max:255'],
            ],
            'name_short' => [
                'description' => 'A new short version of the name of the GroupCategory.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'description' => [
                'description' => 'A new description for the GroupCategory',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
            'style' => [
                'description' => 'The name of a new style in which the category and all the groups that belong to this category should be displayed',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ]
        ];
    }

    /**
     * @param $root
     * @param $args
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws ValidationError
     * @throws \Throwable
     */
    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        $category = GroupCategory::findOrFail($id);

        $name = array_get($args, 'name');
        if($name !== null && $category->name !== $name && GroupCategory::where('name','=',$name)->exists()) {
            throw new ValidationError('There already exists a GroupCategory with the provided name.');
        }

        $category->fill($args);
        $category->saveOrFail();
        return $category;
    }

}