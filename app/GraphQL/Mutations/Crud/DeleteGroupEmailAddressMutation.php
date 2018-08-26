<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-08-18
 * Time: 23:58
 */

namespace App\GraphQL\Mutations\Crud;


use App\GroupEmailAddress;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteGroupEmailAddressMutation extends Mutation
{


    protected $attributes = [
        'name' => 'deleteGroupEmailAddress',
        'description' => 'Deletes an E-mail address of a Group. This mutation will return the `ID` of the deleted `GroupEmailAddress`.',
    ];

    public function type()
    {
        return \GraphQL::type('GroupEmailAddress');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the `GroupEmailAddress` you want to delete.',
                'rules' => ['required','exists:group_email_addresses']
            ]
        ];
    }

    /**
     * @param $root
     * @param $args
     * @return GroupEmailAddress
     * @throws \Exception
     */
    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        /** @var GroupEmailAddress $emailAddress */
        $emailAddress = GroupEmailAddress::findOrFail($id);

        $emailAddress->delete();

        return $emailAddress;
    }

}