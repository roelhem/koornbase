<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 09:53
 */

namespace App\GraphQL\Mutations\Crud;


use App\Membership;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteMembershipMutation extends Mutation
{

    protected $attributes = [
        'name' => 'deleteMembership',
        'description' => 'Removes a membership from the database.'
    ];

    public function type()
    {
        return \GraphQL::type("Membership");
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the Membership that should be deleted.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:memberships'],
            ]
        ];
    }

    /**
     * @param $root
     * @param $args
     * @return Membership
     * @throws \Exception
     */
    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        /** @var Membership $membership */
        $membership = Membership::findOrFail($id);

        $membership->delete();

        return $membership;
    }

}