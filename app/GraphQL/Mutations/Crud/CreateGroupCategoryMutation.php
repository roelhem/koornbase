<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 02:48
 */

namespace App\GraphQL\Mutations\Crud;


use App\GroupCategory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class CreateGroupCategoryMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createGroupCategory',
        'description' => 'Creates a new GroupCategory.'
    ];

    public function type()
    {
        return \GraphQL::type('GroupCategory');
    }

    public function args()
    {
        return [
            'name' => [
                'description' => 'The name for the new GroupCategory',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255','unique:group_categories'],
            ],
            'name_short' => [
                'description' => 'A short version of the name.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'description' => [
                'description' => 'A description of the category.',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
            'style' => [
                'description' => 'The name of the style in which this category and all the groups of this category should be displayed',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ]
        ];
    }

    public function resolve($root, $args)
    {
        return GroupCategory::create($args);
    }

}