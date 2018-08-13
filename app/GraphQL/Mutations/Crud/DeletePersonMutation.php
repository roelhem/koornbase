<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-07-18
 * Time: 18:07
 */

namespace App\GraphQL\Mutations\Crud;


use App\Person;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeletePersonMutation extends Mutation
{

    protected $attributes = [
        'name' => 'deletePerson'
    ];


    public function type()
    {
        return Type::id();
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the Person you want to delete.',
                'rules' => ['required']
            ]
        ];
    }

    /**
     *
     * @param mixed $root
     * @param array $args
     * @return boolean
     * @throws
     */
    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');

        /** @var Person $person */
        $person = Person::findOrFail($id);
        $res = $person->id;

        $person->delete();

        return $res;
    }


}