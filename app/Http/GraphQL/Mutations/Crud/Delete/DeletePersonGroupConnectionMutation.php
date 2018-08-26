<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 02:31
 */

namespace App\Http\GraphQL\Mutations\Crud\Delete;


use App\Group;
use App\Pivots\PersonGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeletePersonGroupConnectionMutation extends Mutation
{

    protected $attributes = [
        'name' => 'deletePersonGroupConnection',
        'description' => 'Removes the connection between a `Person` and a `Group`. After the connection is removed, the Person won\'t be considered to be a member of the group.'
    ];


    public function type()
    {
        return \GraphQL::type('PersonGroupConnection');
    }

    public function args()
    {
        return [
            'person_id' => [
                'description' => 'The `ID` of the Person that needs to be removed from a Group.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:persons,id']
            ],
            'group_id' => [
                'description' => 'The `ID` of the Group from which a Person should be removed.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:groups,id']
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $person_id = array_get($args, 'person_id');
        $group_id = array_get($args, 'group_id');

        /** @var PersonGroup $pivot */
        $pivot = PersonGroup::query()->where([
            'person_id' => $person_id,
            'group_id' => $group_id
        ])->firstOrFail();

        PersonGroup::query()->where([
            'person_id' => $person_id,
            'group_id' => $group_id
        ])->delete();

        return $pivot;
    }
}