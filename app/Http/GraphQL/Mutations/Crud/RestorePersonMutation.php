<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-07-18
 * Time: 18:23
 */

namespace App\Http\GraphQL\Mutations\Crud;


use App\Person;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Rebing\GraphQL\Support\Mutation;

class RestorePersonMutation extends Mutation
{

    protected $attributes = [
        'name' => 'restorePerson'
    ];

    public function type()
    {
        return \GraphQL::type('Person');
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the (soft-deleted) Person that you want to restore.'
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');

        /** @var Builder $trashedPersonQuery */
        $trashedPersonQuery = Person::query()->onlyTrashed();
        /** @var Person $person */
        $person = $trashedPersonQuery->findOrFail($id);

        $person->restore();

        return $person;
    }

}