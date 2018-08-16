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
        $id = array_get($args, 'id');

        $group = Group::query()->where('id', '=', $id)->firstOrFail();
        $group->fill(array_only($args, ['description']));

        $group->saveOrFail();
        return $group;
    }

}