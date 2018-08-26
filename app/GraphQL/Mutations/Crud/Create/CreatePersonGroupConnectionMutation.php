<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 02:01
 */

namespace App\GraphQL\Mutations\Crud\Create;


use App\Group;
use App\Pivots\PersonGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class CreatePersonGroupConnectionMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createPersonGroupConnection',
        'description' => 'Creates a new connection between a provided `Person` and `Group`. This action can be interpreted as \'adding\' a person to a group.'
    ];

    public function type()
    {
        return \GraphQL::type('PersonGroupConnection');
    }

    public function args()
    {
        return [
            'person_id' => [
                'description' => 'The `ID` of the Person that you want to add to a group.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:persons,id'],
            ],
            'group_id' => [
                'description' => 'The `ID` of the Group to which the Person should be added',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:groups,id'],
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $group_id = array_get($args, 'group_id');
        /** @var Group $group */
        $group = Group::findOrFail($group_id);

        $person_id = array_get($args, 'person_id');

        $group->persons()->attach($person_id);

        return PersonGroup::query()->where(['group_id' => $group_id, 'person_id' => $person_id])->firstOrFail();
    }

}