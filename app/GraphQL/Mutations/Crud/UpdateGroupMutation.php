<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-08-18
 * Time: 19:51
 */

namespace App\GraphQL\Mutations\Crud;


use App\Group;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class UpdateGroupMutation extends Mutation
{

    protected $attributes = [
        'name' => 'updateGroup',
        'description' => 'Updates the values of an existing Group.'
    ];

    /**
     * @return mixed|null
     */
    public function type()
    {
        return \GraphQL::type('Group');
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the Group that you want to update.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:groups'],
            ],
            'name' => [
                'description' => 'The new name for the updated Group.',
                'type' => Type::string(),
                'rules' => ['sometimes','required','string','max:255'],
            ],
            'name_short' => [
                'description' => 'The new shorter version of the name for the Group.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'member_name' => [
                'description' => 'The new name for Persons that belong to this Group.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:255'],
            ],
            'description' => [
                'description' => 'The new description for the Group.',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ]
        ];
    }

    /**
     * @param $root
     * @param $args
     * @return \Illuminate\Database\Eloquent\Model|static
     * @throws \Throwable
     */
    public function resolve($root, $args) {

        // Get the group instance
        $id = array_get($args, 'id');
        $group = Group::query()->where('id', '=', $id)->firstOrFail();

        // Check if the name is unique
        $name = array_get($args, 'name');
        if($name !== null && $group->name !== $name) {
            if(Group::query()->where('name','=', $name)->exists()) {
                throw new ValidationError('There already exists a group with the same name.');
            }
        }

        // Update the values of the group
        $group->fill(array_only($args, [
            'name','name_short','member_name','description'
        ]));

        $group->saveOrFail();
        return $group;
    }

}